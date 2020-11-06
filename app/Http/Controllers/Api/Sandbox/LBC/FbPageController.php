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
        Storage::append('public/page_activity.txt', json_encode($request->all(), JSON_PRETTY_PRINT));
        // $page_entries = json_decode(json_encode($request->all(), true));

        // $fbPageService->receiveTestPageEntryEvent($page_entries);

        return response("Received content", 200);
    }

    public function receiveDataFromWebhook(Request $request, FbPageService $fbPageService) {
        $page_entries = json_decode(json_encode($request->all(), true));

        $fbPageService->receivePageEntryEvent($page_entries);

        return response("Received content", 200);
    }

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
