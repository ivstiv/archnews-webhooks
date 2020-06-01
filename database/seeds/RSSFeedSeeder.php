<?php

use App\RSSFeed;
use Illuminate\Database\Seeder;

class RSSFeedSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('rss_feeds')->insert([
            'name' => 'Arch News',
            'url' => 'https://www.archlinux.org/feeds/news/',
            'lastItemTime' => '2020-01-01',
        ]);

        $rssFeed = RSSFeed::where('name', 'Arch News')->get()->first();
        $items = $rssFeed->getItemsSince($rssFeed->lastItemTime);
        $newLastItemTime = $items->get(0)['time'];
        $rssFeed->update([ 'lastItemTime' => $newLastItemTime]);
    }
}
