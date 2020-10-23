<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use App\Services\LBC\FbPageService;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FbPageController extends Controller
{
    public function __construct() {
        $this->access_token = '';
        $this->page_id = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        // $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc-tst.processes.quandago.app/api/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://dti-tst.processes.quandago.dev/api/']);
    }

    public function authAPITest() {
        $auth_api_request = $this->vanad_client->post('auth/authenticate/false', [
            'auth' => [
                env('VANAD_API_USER'), env('VANAD_API_PASS')
            ],
            'json' => [
                'Audience' => 'https://lbc-tst.processes.quandago.app'
                // 'Audience' => 'https://dti-tst.processes.quandago.dev'
            ]
        ]);

        $auth_api_sc = $auth_api_request->getStatusCode();
        if ($auth_api_sc == 200) {
            $auth_api_response = $auth_api_request->getBody()->getContents();            
            // return $auth_api_response;
            return response()->json([
                'ProcessRunnerToken' => $auth_api_response
            ], 200);
        }

        return response()->json([
            'message' => 'An unexpected error has occured.'
        ], 500);
    }

    public function processAPITest(Request $request) {
        $process_runner_token = $request->input('process_runner_token');
        
        $create_process_request = $this->vanad_client->post('package/process/contact', [
            'json' => [
                'Assignee' => 'Admin',
                'Priority' => 'M',
                'Type' => 'FC',
                'RelatePath' => 'cCustomer:106',
                'Category' => 'Contacted',
                'Subcategory' => 'Contacted_CustomerNotAvailable',
                'Description' => 'Test create contact',
                'FormData' => [
                    'name' => 'Juan dela Cruz',
                    'message' => 'example message Oct 15, 2020 (3)',
                    'page_id' => '1234567890',
                    'post_id' => '1234567890',
                    'comment_id' => '1234567890',
                    'created_time' => '1602762813'
                ],
                'Remarks' => 'Test remarks.',
                'RelateCode' => 'InteractionCustomer'
            ],
            'headers' => [
                'ProcessRunnerToken' => $process_runner_token
            ]
        ]);

        $create_process_sc = $create_process_request->getStatusCode();
        if ($create_process_sc == 200) {
            $create_process_response = json_decode($create_process_request->getBody()->getContents());
            // return $create_process_response->CaseId . " " . $create_process_response->ContactId;            
            return response()->json($create_process_response, 200);
        }

        return response()->json([
            'message' => 'An unexpected error has occured.'
        ], 500);
    }

    public function receiveDataFromTestWebhook(Request $request, FbPageService $fbPageService) {
        $page_entries = json_decode(json_encode($request->all(), true));

        $fbPageService->receiveTestPageEntryEvent($page_entries);

        return response("Received content", 200);      
    }

    public function receiveDataFromWebhook(Request $request, FbPageService $fbPageService) {
        $page_entries = json_decode(json_encode($request->all(), true));

        // $fbPageService->receivePageEntryEvent($page_entries);        

        return response("Received content", 200);
    }

    public function createCommentReply(Request $request, FbPageService $fbPageService) {
        $comment_reply_response = $fbPageService->handleCommentReplyRequest($request);
        return $comment_reply_response;
    }

    public function hidePostComment(Request $request, FbPageService $fbPageService) {
        $hide_comment_response = $fbPageService->handleHideCommentRequest($request);
        return $hide_comment_response;
    }
}
