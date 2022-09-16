<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use App\Services\Sandbox\LBC\TwitterFeedService;
use Illuminate\Http\Request;

class TwitterFeedController extends Controller
{
    public function registerWebhook(Request $request, TwitterFeedService $twitterFeedService) {
        $register_webhook_response = $twitterFeedService->handleRegisterWebhookEvent($request);
        return $register_webhook_response;
    }

    public function receiveDataFromTestWebhook(Request $request, TwitterFeedService $twitterFeedService) {
        $receive_data_response = $twitterFeedService->handleTestFeedActivityEvent($request);
        return $receive_data_response;
    }

    public function receiveDataFromWebhook(Request $request, TwitterFeedService $twitterFeedService) {
        $receive_data_response = $twitterFeedService->handleFeedActivityEvent($request);
        return $receive_data_response;
    }

    public function receiveCRCToken(Request $request, TwitterFeedService $twitterFeedService) {
        $receive_crc_response = $twitterFeedService->handleReceiveCRCTokenEvent($request);
        return $receive_crc_response;
    }

    public function getRequestTokenTest(TwitterFeedService $twitterFeedService) {
        $get_request_token_response = $twitterFeedService->handleGetRequestTokenEvent();
        return $get_request_token_response;
    }

    public function replyToTweet(Request $request, TwitterFeedService $twitterFeedService) {
        $reply_to_tweet_response = $twitterFeedService->handleReplyToTweetEvent($request);
        return $reply_to_tweet_response;
    }
}
