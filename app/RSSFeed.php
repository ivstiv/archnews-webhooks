<?php

namespace App;

use Carbon\Carbon;
use SimplePie;
use Illuminate\Database\Eloquent\Model;

class RSSFeed extends Model {

    protected $fillable = ['name', 'url', 'lastItemTime'];
    // by laravel's conventions table's name should be r_s_s_feeds which doesn't look good.. :/
    protected $table = 'rss_feeds';
    public $timestamps = false;

    public function getItemsSince($lastItemTime) {
        $url = $this->url;
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();

        $itemsInfo = [];
        foreach($feed->get_items() as $item) {

            // we need the time in carbon object to compare it
            $carbonDate = Carbon::parse($item->get_date());
            // removing special html chars
            $formattedTitle = preg_replace("/&#?[a-z0-9]{2,8};/i","", $item->get_title());

            array_push($itemsInfo, [
                'title' => $formattedTitle,
                'link' => $item->get_link(),
                'time' => $carbonDate
            ]);
        }

        // we cant just compare times in strings
        $carbonLastItemTime = Carbon::parse($lastItemTime);

        return collect($itemsInfo)->takeUntil(function ($item) use ($carbonLastItemTime) {
            return $item['time'] <= $carbonLastItemTime;
        });
    }

    public function webhooks() {
        // laravel cant deduce RSSFeed name right so we need to to specify the pivot
        return $this->belongsToMany('App\Webhook', 'rssfeed_webhook', 'rssfeed_id', 'webhook_id');
    }
}
