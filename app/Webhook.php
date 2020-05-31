<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model {

    protected $fillable = ['url', 'type'];
    public $timestamps = false;


    public function send($title, $link) {
        // check type
        // use appropriate send function
    }

    public function rssFeeds() {
        // laravel cant deduce RSSFeed name right so we need to to specify the pivot
        return $this->belongsToMany('RSSFeed', 'rssfeed_webhook', 'webhook_id', 'rssfeed_id');
    }
}
