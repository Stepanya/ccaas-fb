<?php

namespace App\Services\LBC;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TwitterFeedService {
    public function handleTestFeedActivityEvent() {
        return response("Request received.", 200);
    }
}
