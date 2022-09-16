<?php

namespace App\Services\Sandbox\LBC;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TwitterFeedService {
    public function __construct() {
        $this->test_connection = new TwitterOAuth(env('TWITTER_APP_API_KEY'), env('TWITTER_APP_API_SECRET'), env('TWITTER_APP_ACCESS_TOKEN'), env('TWITTER_APP_ACCESS_TOKEN_SECRET'));
        $this->connection = new TwitterOAuth(env('LBC_CCAAS_WEBHOOK_APP_API_KEY'), env('LBC_CCAAS_WEBHOOK_APP_API_SECRET'), env('LBC_CCAAS_WEBHOOK_APP_ACCESS_TOKEN'), env('LBC_CCAAS_WEBHOOK_APP_ACCESS_TOKEN_SECRET'));
    }

    // Prod endpoint functions
    public function handleRegisterWebhookEvent($request) {
      $url = $request->input('url');
      $webhook_registration_response = $this->connection->post('account_activity/all/prod/webhooks', ['url' => $url]);

      return response()->json($webhook_registration_response);
    }

    public function handleReceiveCRCTokenEvent($request) {
      $crc_token = $request->input('crc_token');

      Log::info('Twitter CRC Token: ' . $crc_token);
      return response()->json($this->getChallengeResponse($crc_token), 200);
    }

    private function getChallengeResponse($token) {
        $hash = hash_hmac('sha256', $token, env('LBC_CCAAS_WEBHOOK_APP_API_SECRET'), true);
        return [
            'response_token' => 'sha256=' . base64_encode($hash)
        ];
    }

    public function handleFeedActivityEvent($request) {
      $feed_entries = json_decode(json_encode($request->all(), true));

      $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));

      if (isset($feed_entries->tweet_create_events)) {
        foreach ($feed_entries->tweet_create_events as $event) {
          Storage::append('public/page_activity.txt',
          '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
          'message: ' . $event->text . PHP_EOL .
          'status_id: ' . $event->id . PHP_EOL .
          'name: ' . $event->user->name . PHP_EOL .
          'screen_name: ' . $event->user->screen_name . PHP_EOL
          );
        }
      }

      // Storage::append('public/page_activity.txt', json_encode($request->all(), JSON_PRETTY_PRINT));
      return response("Received content", 200);
    }

    public function handleReplyToTweetEvent($request) {
        $screen_name = $request->input('screen_name');
        $message = $request->input('message');
        $status_id = $request->input('status_id');

        // Replace with $this->connection on production
        $tweet_reply_response = $this->test_connection->post('statuses/update', ['status' => "@$screen_name $message", 'in_reply_to_status_id' => $status_id]);

        $tweet_reply_response_sc = $this->test_connection->getLastHttpCode();

        return response()->json($tweet_reply_response, $tweet_reply_response_sc);
    }

    // Sandbox endpoint functions
    public function handleTestFeedActivityEvent($request) {
        $crc_token = $request->input('crc_token');

        if (isset($crc_token)) {
            Log::info('Twitter CRC Token: ' . $crc_token);
            return response()->json($this->getTestChallengeResponse($crc_token), 200);
        } else {
            $feed_entries = json_decode(json_encode($request->all(), true));

            if (isset($feed_entries->tweet_create_events)) {
              foreach ($feed_entries->tweet_create_events as $event) {
                Storage::append('public/page_activity.txt',
                'message: ' . $event->text . PHP_EOL .
                'status_id: ' . $event->id . PHP_EOL .
                'name: ' . $event->user->name . PHP_EOL .
                'screen_name: ' . $event->user->screen_name . PHP_EOL
                );
              }
            }

            // Storage::append('public/page_activity.txt', json_encode($request->all(), JSON_PRETTY_PRINT));
            return response("Received content", 200);
        }
    }

    private function getTestChallengeResponse($token) {
        $hash = hash_hmac('sha256', $token, env('TWITTER_APP_API_SECRET'), true);
        return [
            'response_token' => 'sha256=' . base64_encode($hash)
        ];
    }
}
