<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use App\Services\Sandbox\LBC\TwitterFeedService;
use Illuminate\Http\Request;

class TwitterFeedController extends Controller
{
    public function receiveDataFromTestWebhook(Request $request, TwitterFeedService $twitterFeedService) {
        $receive_data_response = $twitterFeedService->handleTestFeedActivityEvent($request);
        return $receive_data_response;
    }

    public function getRequestTokenTest(TwitterFeedService $twitterFeedService) {
        $get_request_token_response = $twitterFeedService->handleGetRequestTokenEvent();
        return $get_request_token_response;
    }
}
