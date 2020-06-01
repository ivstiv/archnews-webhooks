<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('img/image/favicon-32x32.png') }}" />
        <title>Arch News Webhooks</title>
        <meta name="description" content="A service to keep you updated with the latest Arch Linux News on Discord and Slack.">
        <meta name="keywords" content="arch, linux, news, rss, feed, discord, slack, webhook, updates, ivstiv">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/style.css')}}">
        <script defer src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <div class="area" >
            <div class="top-part">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
                <div class="webhook-input">
                    <input type="text" name="webhook-input" id="webhook-input" placeholder="Webhook url...">
                    <input type="button" id="webhook-button" value="Add webhook">
                    <a id="link-submit" href="{{ route('addWebhook') }}"></a>
                    <h3 id="error-msg" class="error"></h3>
                </div>
            </div>
            <div class="bottom-part">
                <div class="column">
                    The project fetches information from <a href="https://www.archlinux.org/feeds/news/" target="_blank">this</a> RSS feed
                    every hour. Upon update it will send you a notification
                    with hyperlink to the article according to your webhook.</br></br>
                    Example:
                    <img src="{{ asset('img/discord_example.png') }}" alt="discord_example" class="example">
                </div>
                <div class="column">
                    <h1>FAQ:</h1>
                    <ul style="list-style: circle;">
                        <li>If you want to stop receiving updates from this service, simply delete the webhook from your channel.</li>
                        <li><a href="{{ route('discord') }}" target="_blank">How to create a webhook in Discord?</a></li>
                        <li><a href="{{ route('slack') }}" target="_blank">How to create a webhook in Slack?</a></li>
                        <li><a href="https://github.com/Ivstiv/archnews-webhooks" target="_blank"><h3>Github</h3></a></li>
                        <li>For other feeds to be integrated you can find me in <a href="https://discord.gg/VMSDGVD" target="_blank">my discord server</a> :)</li>
                    </ul>
                </div>
            </div>
            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </body>
</html>
