<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use App\Services\Sandbox\LBC\InstagramFeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InstagramFeedController extends Controller
{
    public function receiveDataFromTestWebhook(Request $request, InstagramFeedService $instagramFeedService) {
        $receive_data_response = $instagramFeedService->handleTestFeedActivityEvent($request);
        return $receive_data_response;
    }
}
