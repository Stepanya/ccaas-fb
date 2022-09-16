<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentReplyRequest;
use App\Http\Requests\HidePostCommentRequest;
use App\Services\Sandbox\LBC\FbPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FbPageController extends Controller
{
    public function receiveDataFromTestWebhook(Request $request, FbPageService $fbPageService) {
        Storage::append('public/comments.txt', json_encode($request->all(), JSON_PRETTY_PRINT) . PHP_EOL);

        $page_entries = json_decode(json_encode($request->all(), true));

        $fbPageService->receiveTestPageEntryEvent($page_entries);

        return response("Received content", 200);
    }

    // Test routes
    public function newEndpointTest(Request $request) {
        Storage::append('public/comments.txt', json_encode($request->all(), JSON_PRETTY_PRINT) . PHP_EOL);
        // Storage::append('public/comments.txt', "Hello" . PHP_EOL);
        // $page_entries = json_decode(json_encode($request->all(), true));
        //
        // $fbPageService->receiveTestPageEntryEvent($page_entries);

        return response("Received content", 200);
    }

    public function receiveHubToken(Request $request) {
        if ($request->input('hub_mode') === 'subscribe' && $request->input('hub_verify_token') === '123') {
                // Log::info('Hub Mode: ' . $request->input('hub_mode') . ' Token: ' . '123' . ' Hub Challenge: ' . $request->input('hub_challenge'));
                return response($request->input('hub_challenge'), 200);
        }
          return response("Invalid token!", 400);
    }

    // Test routes
    public function receiveNewHubToken(Request $request) {
        if ($request->input('hub_mode') === 'subscribe' && $request->input('hub_verify_token') === '123') {
                // Log::info('Hub Mode: ' . $request->input('hub_mode') . ' Token: ' . '123' . ' Hub Challenge: ' . $request->input('hub_challenge'));
                return response($request->input('hub_challenge'), 200);
        }
          return response("Invalid token!", 400);
    }

    // public function receiveDataFromWebhook(Request $request, FbPageService $fbPageService) {
    //     $page_entries = json_decode(json_encode($request->all(), true));
    //
    //     $fbPageService->receivePageEntryEvent($page_entries);
    //
    //     return response("Received content", 200);
    // }

    public function createCommentReply(CreateCommentReplyRequest $request, FbPageService $fbPageService) {
        // Get validated inputs from request
        $req_body = json_decode(json_encode($request->validated()));

        $comment_reply_response = $fbPageService->handleCommentReplyRequest($req_body);
        return response()->json([
            'success' => $comment_reply_response['success'],
            'message' => $comment_reply_response['message'],
        ], $comment_reply_response['status_code']);
    }

    public function hidePostComment(HidePostCommentRequest $request, FbPageService $fbPageService) {
        // Get validated inputs from request
        $req_body = json_decode(json_encode($request->validated()));

        $hide_comment_response = $fbPageService->handleHideCommentRequest($req_body);
        return response()->json([
            'success' => $hide_comment_response['success'],
            'message' => $hide_comment_response['message'],
        ], $hide_comment_response['status_code']);
    }
}
