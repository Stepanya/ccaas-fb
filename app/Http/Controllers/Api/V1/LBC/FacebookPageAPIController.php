<?php

namespace App\Http\Controllers\Api\V1\LBC;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FacebookPageAPIController extends Controller
{
    public function __construct() {
        $this->access_token = 'EAAD2eb8YYhQBAKRFVp268QlHeC0FnUazV81BPDHJqEIjhhEej5u1UwLn7UwgzqfNzRoN5ZCZCIKMaRvTHu2qwo4cuagH3w4SZBZAbnmDsYSb7gTnrR1ZCutZCZAHpfzk930I70camhKlEXBhjDVH2LSfOf9EjdHvqzFyKBPbxcZAWQZDZD';
        $this->page_id = '100878368385562';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
    }

    public function createCommentReply(Request $request) {
        $message = $request->input('message');
        $comment_id = $request->input('comment_id');
        
        // $comment_replies_request = $this->client->get($comment_id.'/comments?access_token='.$this->access_token);
        $reply_to_comment_request = $this->client->post($comment_id.'/comments', 
                ['json' => 
                    [
                        'message' => $message,
                        'access_token' => $this->access_token
                    ]
                ]
            );
        $reply_to_comment_sc = $reply_to_comment_request->getStatusCode();

        if ($reply_to_comment_sc == 200) {
            $reply_to_comment = json_decode($reply_to_comment_request->getBody()->getContents());            
            return response()->json($reply_to_comment, 200);
        }

        return response()->json([
            'message' => 'An unexpected error has occured'
        ], 500);
    }
}
