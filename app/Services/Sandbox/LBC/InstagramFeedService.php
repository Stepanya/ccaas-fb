<?php

namespace App\Services\Sandbox\LBC;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InstagramFeedService {
    public function handleTestFeedActivityEvent($request) {
        Storage::append('public/page_activity.txt', json_encode($request->all(), JSON_PRETTY_PRINT));
        return response("Received content", 200);
    }
}
