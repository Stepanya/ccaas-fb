<?php

namespace App\Http\Controllers\Api\Sandbox;

use App\Http\Controllers\Controller;
use App\Services\LBC\TwitterFeedService;
use Illuminate\Http\Request;

class TwitterFeedController extends Controller
{
    public function receiveDataFromTestWebhook(TwitterFeedService $twitterFeedService) {
        $receive_data_response = $twitterFeedService->handleTestFeedActivityEvent();
        return $receive_data_response;
    }
}
