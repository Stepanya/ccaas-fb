<?php

namespace App\Services\Sandbox\LBC;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TwitterFeedService {
    public function __construct() {
        $this->connection = new TwitterOAuth(env('TWITTER_APP_API_KEY'), env('TWITTER_APP_API_SECRET'), env('TWITTER_APP_ACCESS_TOKEN'), env('TWITTER_APP_ACCESS_TOKEN_SECRET'));
    }

    public function handleTestFeedActivityEvent($request) {
        $crc_token = $request->input('crc_token');
        
        if (isset($crc_token)) {
            return response()->json($this->getChallengeResponse($crc_token), 200);
        } else {
            Storage::append('public/page_activity.txt', json_encode($request->all(), JSON_PRETTY_PRINT));
            return response("Received content", 200);
        }
    }

    private function getChallengeResponse($token) {
        $hash = hash_hmac('sha256', $token, env('TWITTER_APP_API_SECRET'), true);
        return [
            'response_token' => 'sha256=' . base64_encode($hash)
        ];
    }
}
