<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use SimplePie;
use GuzzleHttp\RequestOptions;

class Webhook extends Model {

    protected $fillable = ['url', 'type'];
    public $timestamps = false;
    private static $guzzleClientInstance = null;

    // we can reuse the client between instances :)
    public static function getGuzzleClient() {
        if(Webhook::$guzzleClientInstance == null) {
            Webhook::$guzzleClientInstance = new \GuzzleHttp\Client(['http_errors' => false]);
        }
        return Webhook::$guzzleClientInstance;
    }

    public function test() {
        $url = 'http://localhost/news.xml';
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();
        return $feed->get_item(0)->get_date('Y-m-d H:i:s');
    }

    public function send($msg, $link = '') {
        if($this->type == 'discord') {
            return $this->discordSend($msg, $link);
        }else if($this->type == 'slack') {
            return $this->slackSend($msg, $link);
        }else{
            error_log("Invalid webhook type: ".$this->type);
        }
    }

    private function slackSend($msg, $link) {
        // if the link is empty don't create a hyperlink, just a normal message
        $body = [];
        if($link == '') {
            $body = [
                'username' => 'Arch News',
                'icon_url' => 'https://cdn0.iconfinder.com/data/icons/flat-round-system/512/archlinux-512.png',
                'text' => $msg,
            ];
        }else {
            $body = [
                'username' => 'Arch News',
                'icon_url' => 'https://cdn0.iconfinder.com/data/icons/flat-round-system/512/archlinux-512.png',
                'text' =>"<${link} | ${msg}>"
            ];
        }

        $response = Webhook::getGuzzleClient()->post($this->url, [ RequestOptions::JSON => $body]);
        return $response->getStatusCode();
    }

    private function discordSend($msg, $link) {
        // if the link is empty don't create an embed, just a normal message
        $body = [];
        if($link == '') {
            $body = [
                'username' => 'Arch News',
                'avatar_url' => 'https://cdn0.iconfinder.com/data/icons/flat-round-system/512/archlinux-512.png',
                'content' => $msg,
            ];
        }else {
            $body = [
                'username' => 'Arch News',
                'avatar_url' => 'https://cdn0.iconfinder.com/data/icons/flat-round-system/512/archlinux-512.png',
                'embeds' => [
                    (object) [
                        'title' => $msg,
                        'url' => $link
                    ]
                ]
            ];
        }

        $response = Webhook::getGuzzleClient()->post($this->url, [ RequestOptions::JSON => $body]);
        return $response->getStatusCode();
    }

    public function rssFeeds() {
        // laravel can't deduce RSSFeed name right so we need to to specify the pivot
        return $this->belongsToMany('RSSFeed', 'rssfeed_webhook', 'webhook_id', 'rssfeed_id');
    }
}
