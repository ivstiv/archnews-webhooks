<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RSSFeed extends Model {

    protected $fillable = ['name', 'url', 'lastBuildDate'];
    // by laravel's conventions table's name should be r_s_s_feeds which is lame.. :/
    protected $table = 'rss_feeds';
    public $timestamps = false;

    public function webhooks() {
        // laravel cant deduce RSSFeed name right so we need to to specify the pivot
        return $this->belongsToMany('Webhook', 'rssfeed_webhook', 'rssfeed_id', 'webhook_id');
    }
}
