<?php

namespace App\Console;

use App\RSSFeed;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // general arch news checking
        $schedule->call(function () {
            $rssFeed = RSSFeed::where('name', 'Arch News')->get()->first();
            $items = $rssFeed->getItemsSince($rssFeed->lastItemTime);
            // check if there are new items
            if(count($items) > 0) {
                // update the new last time
                $newLastItemTime = $items->get(0)['time'];
                $rssFeed->update([ 'lastItemTime' => $newLastItemTime]);
                // loop over all new items and webhooks
                $items->each(function ($item) use ($rssFeed) {
                    $rssFeed->webhooks()->each(function ($webhook) use ($item, $rssFeed) {
                        $statusCode = $webhook->send($item['title'], $item['link']);
                        // if the webhook returns code 400 - delete it
                        if ($statusCode > 400) {
                            // delete from the pivot table
                            $webhook->rssFeeds()->detach($rssFeed->id);
                            // delete the object
                            $webhook->delete();
                        }
                    });
                });
            }
        })->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
