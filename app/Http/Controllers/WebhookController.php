<?php

namespace App\Http\Controllers;


use App\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebhookController extends Controller {

    public function addWebhook(Request $request) {

        $validator = Validator::make($request->all(), [
            'webhook' => [
                'bail',
                'required',
                'unique:App\Webhook,url',
                'regex:(https://discord.com/api/webhooks|https://hooks.slack.com/services)'
            ]
        ]);

        if($validator->fails()) {
            $errorMsg = $validator->errors()->first('webhook');
            return $this->sendError($errorMsg);
        }

        // set type of the webhook
        $type='';
        if (Str::contains($request->input('webhook'), 'discord')) {
            $type='discord';
        }elseif (Str::contains($request->input('webhook'), 'slack')) {
            $type='slack';
        }else{
            return $this->sendError("Unknown webhook!");
        }

        $webhook = new Webhook([
            'url' => $request->input('webhook'),
            'type' => $type
        ]);

        // check if the webhook actually works (> 400 = fail)
        $statusCode = $webhook->send("You have successfully subscribed for updates from Arch News.");
        if ($statusCode > 400) {
            return $this->sendError("Invalid webhook!");
        }

        $webhook->save();
        return json_encode(["status" => "success", "msg" => "The webhook was added successfully!"]);
    }

    private function sendError($msg) {
        return json_encode(["status" => "error", "msg" => $msg]);
    }
}
