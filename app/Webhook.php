<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use SimplePie;

class Webhook extends Model {

    protected $fillable = ['url', 'type'];
    public $timestamps = false;

    public function test() {
        $url = 'http://localhost/news.xml';
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();
        return $feed->get_item(0)->get_date('Y-m-d H:i:s');
    }

    public function isValid() {
        if($this->type == 'discord') {
            $this->isValidDiscord();
        }else if($this->type == 'slack') {
            $this->isValidSlack();
        }else{
            error_log("Invalid webhook type: ".$this->type);
        }
    }

    private function isValidDiscord() {

    }

    private function isValidSlack() {

    }

    public function send($title, $link) {
        if($this->type == 'discord') {
            $this->discordSend($title, $link);
        }else if($this->type == 'slack') {
            $this->slackSend($title, $link);
        }else{
            error_log("Invalid webhook type: ".$this->type);
        }
    }

    private function slackSend($title, $link) {

    }

    private function discordSend($title, $link) {

    }

    public function rssFeeds() {
        // laravel cant deduce RSSFeed name right so we need to to specify the pivot
        return $this->belongsToMany('RSSFeed', 'rssfeed_webhook', 'webhook_id', 'rssfeed_id');
    }
}
