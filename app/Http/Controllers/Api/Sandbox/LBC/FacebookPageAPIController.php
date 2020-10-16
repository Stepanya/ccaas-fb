<?php

namespace App\Http\Controllers\Api\Sandbox\LBC;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FacebookPageAPIController extends Controller
{
    public function __construct() {
        // $this->access_token = 'EAALpK7eInSoBADN9b6ASjHXf2IZB0KFGk4C3wcOZALllsyZBZCZCvztDP3hQuiGV0hekQ6Wrs8rLZAV40kGDDmrUMzEMLsFgNS25UyNAInZCFjt9ZBTUINzF7Wb1MZB3L0BkDBnw89TRBlRRG4YYv3yWNXl0IoT1XRhLdba0qvwqfZAoi94QzsUDhhenZCTymNjtskZD';
        $this->access_token = '';
        $this->page_id = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc-tst.processes.quandago.app/api/']);
    }

    public function authAPITest() {
        $auth_api_request = $this->vanad_client->post('auth/authenticate/false', [
            'auth' => [
                env('VANAD_API_USER'), env('VANAD_API_PASS')
            ],
            'json' => [
                'Audience' => 'https://lbc-tst.processes.quandago.app'
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
                'Category' => 'BusinessName',
                'Subcategory' => 'Covid19_Others',
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

    public function receiveDataFromTestWebhook(Request $request) {
        $process_runner_token = '';
        $page_entries = json_decode(json_encode($request->all(), true));
            
        foreach ($page_entries as $page_entry) {
            foreach ($page_entries->entry as $entry) {
                $this->page_id = $entry->id;

                foreach ($entry->changes as $change) {
                    // If comment is made by page
                    if ($change->value->item === 'comment' && $change->value->verb === "add" && $change->value->from->id === $this->page_id) {
                        // Current datetime
                        $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
                        // Entry time
                        $entry_datetime = new \DateTime(date('Y-m-d H:i:s', $entry->time), new \DateTimeZone('UTC'));
                        $entry_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
                        // Value created_at
                        $created_at_datetime = new \DateTime(date('Y-m-d H:i:s', $change->value->created_time), new \DateTimeZone('UTC'));
                        $created_at_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
                        // $date->setTimestamp($change->value->created_time);
                        // Storage::append('public/comments_lbc.txt', date('Y-m-d H:i:s', $change->value->created_time) . PHP_EOL . 
                        Storage::append('public/comments_tritel_page.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
                        'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
                        'post_id: ' . $change->value->post_id . PHP_EOL .
                        'comment_id: ' . $change->value->comment_id . PHP_EOL .
                        'parent_id: ' . $change->value->parent_id . PHP_EOL .
                        'created_time: ' . $change->value->created_time . PHP_EOL .
                        'from.id: ' . $change->value->from->id . PHP_EOL .
                        'from.name: ' . $change->value->from->name . PHP_EOL .
                        'message: ' . $change->value->message . PHP_EOL);
                        return response("Received content", 200);
                        // If comment is made by users
                     } else if ($change->value->item === 'comment' && $change->value->verb === "add") {
                    // } else if ($change->value->item === 'comment' && $change->value->from->id === '5268518223162209') {
                        // Current datetime
                        $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
                        // Entry time
                        $entry_datetime = new \DateTime(date('Y-m-d H:i:s', $entry->time), new \DateTimeZone('UTC'));
                        $entry_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
                        // Value created_at
                        $created_at_datetime = new \DateTime(date('Y-m-d H:i:s', $change->value->created_time), new \DateTimeZone('UTC'));
                        $created_at_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
                        // $date->setTimestamp($change->value->created_time);
                        // Storage::append('public/comments.txt', date('Y-m-d H:i:s', $change->value->created_time) . PHP_EOL . 
                        Storage::append('public/comments_tritel_user.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
                        'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
                        'post_id: ' . $change->value->post_id . PHP_EOL .
                        'comment_id: ' . $change->value->comment_id . PHP_EOL .
                        'parent_id: ' . $change->value->parent_id . PHP_EOL .
                        'created_time: ' . $change->value->created_time . PHP_EOL .
                        'from.id: ' . $change->value->from->id . PHP_EOL .
                        'from.name: ' . $change->value->from->name . PHP_EOL .
                        'message: ' . $change->value->message . PHP_EOL);

                        // Get ProcessRunnerToken via Authenticate API
                        $auth_api_request = $this->vanad_client->post('auth/authenticate/false', [
                            'auth' => [
                                env('VANAD_API_USER'), env('VANAD_API_PASS')
                            ],
                            'json' => [
                                'Audience' => 'https://dti-tst.processes.quandago.dev'
                            ]
                        ]);
                
                        $auth_api_sc = $auth_api_request->getStatusCode();
                        // If successful, pass ProcessRunnerToken to $process_runner_token variable
                        if ($auth_api_sc == 200) {
                            $auth_api_response = $auth_api_request->getBody()->getContents();            
                            $process_runner_token = $auth_api_response;
                        } 
                        
                        // else {
                        //     return response()->json([
                        //         'message' => 'An unexpected error has occured (Authenticate).'
                        //     ], 500);
                        // }

                        // Create Process via Process API
                        $create_process_request = $this->vanad_client->post('package/process/contact', [
                            'json' => [
                                'Assignee' => 'Admin',
                                'Priority' => 'M',
                                'Type' => 'FC',
                                'RelatePath' => 'cCustomer:106',
                                'Category' => 'BusinessName',
                                'Subcategory' => 'Covid19_Others',
                                'Description' => 'Test create contact',
                                'FormData' => [
                                    'name' => $change->value->from->name,
                                    'message' => $change->value->message,
                                    'page_id' => $this->page_id,
                                    'post_id' => $change->value->post_id,
                                    'comment_id' => $change->value->comment_id,
                                    'user_id' => $change->value->from->id,
                                    'created_time' => $change->value->created_time
                                ],
                                'Remarks' => 'Test remarks.',
                                'RelateCode' => 'InteractionCustomer'
                            ],
                            'headers' => [
                                'ProcessRunnerToken' => $process_runner_token
                            ]
                        ]);
                
                        $create_process_sc = $create_process_request->getStatusCode();
                        // If successful, append CaseId and ContactId to text file.
                        if ($create_process_sc == 200) {
                            $create_process_response = json_decode($create_process_request->getBody()->getContents());            
                            Storage::append('public/comments_tritel_user.txt', 
                                'CaseId: ' . $create_process_response->CaseId . PHP_EOL . 
                                'ContactId: ' . $create_process_response->ContactId . PHP_EOL);
                        } 
                        
                        // else {
                        //     return response()->json([
                        //         'message' => 'An unexpected error has occured (Process).'
                        //     ], 500);
                        // }

                        return response("Received content", 200);
                    }
                }   
            }
        }   
    }

    public function receiveDataFromWebhook(Request $request) {
    //     $page_entries = json_decode(json_encode($request->all(), true));
            
    //     foreach ($page_entries as $page_entry) {
    //         foreach ($page_entries->entry as $entry) {
    //             $this->page_id = $entry->id;

    //             foreach ($entry->changes as $change) {
    //                 // If comment is made by page
    //                 if ($change->value->item === 'comment' && $change->value->verb === "add" && $change->value->from->id === $this->page_id) {
    //                     // Current datetime
    //                     $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
    //                     // Entry time
    //                     $entry_datetime = new \DateTime(date('Y-m-d H:i:s', $entry->time), new \DateTimeZone('UTC'));
    //                     $entry_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
    //                     // Value created_at
    //                     $created_at_datetime = new \DateTime(date('Y-m-d H:i:s', $change->value->created_time), new \DateTimeZone('UTC'));
    //                     $created_at_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
    //                     // $date->setTimestamp($change->value->created_time);
    //                     // Storage::append('public/comments_lbc.txt', date('Y-m-d H:i:s', $change->value->created_time) . PHP_EOL . 
    //                     Storage::append('public/comments_lbc.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
    //                     'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
    //                     'post_id: ' . $change->value->post_id . PHP_EOL .
    //                     'comment_id: ' . $change->value->comment_id . PHP_EOL .
    //                     'parent_id: ' . $change->value->parent_id . PHP_EOL .
    //                     'created_time: ' . $change->value->created_time . PHP_EOL .
    //                     'from.id: ' . $change->value->from->id . PHP_EOL .
    //                     'from.name: ' . $change->value->from->name . PHP_EOL .
    //                     'message: ' . $change->value->message . PHP_EOL);
    //                     return response("Received content", 200);
    //                     // If comment is made by users
    //                  } else if ($change->value->item === 'comment' && $change->value->verb === "add") {
    //                 // } else if ($change->value->item === 'comment' && $change->value->from->id === '5268518223162209') {
    //                     // Current datetime
    //                     $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
    //                     // Entry time
    //                     $entry_datetime = new \DateTime(date('Y-m-d H:i:s', $entry->time), new \DateTimeZone('UTC'));
    //                     $entry_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
    //                     // Value created_at
    //                     $created_at_datetime = new \DateTime(date('Y-m-d H:i:s', $change->value->created_time), new \DateTimeZone('UTC'));
    //                     $created_at_datetime->setTimeZone(new \DateTimeZone('Asia/Manila'));
    //                     // $date->setTimestamp($change->value->created_time);
    //                     // Storage::append('public/comments.txt', date('Y-m-d H:i:s', $change->value->created_time) . PHP_EOL . 
    //                     Storage::append('public/comments.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
    //                     'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
    //                     'post_id: ' . $change->value->post_id . PHP_EOL .
    //                     'comment_id: ' . $change->value->comment_id . PHP_EOL .
    //                     'parent_id: ' . $change->value->parent_id . PHP_EOL .
    //                     'created_time: ' . $change->value->created_time . PHP_EOL .
    //                     'from.id: ' . $change->value->from->id . PHP_EOL .
    //                     'from.name: ' . $change->value->from->name . PHP_EOL .
    //                     'message: ' . $change->value->message . PHP_EOL);
    //                     return response("Received content", 200);
    //                 }
    //             }   
    //         }
    //     }
    }

    public function createCommentReply(Request $request) {
        $message = $request->input('message');
        $comment_id = $request->input('comment_id');
        $this->access_token = $this->getPageAccessToken($request->input('page_id'));

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

    private function getPageAccessToken($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return 'EAADpafV3ZCmEBAGJY9f9sjN2AZAy8Pq8DX9WXhEwGFWZCO4fwnktX4twKtVtpwV4mitGZBZAzLRIRYjKnj7grbDtWUsLnt5Ky04ZCamNhwZAd71O348cMS3Vk2ZA3ibN6W9cs0djTqbvBilrAHkBZBIegmUCmGJYeT3SQnD01LjAXp9W0tyjdk3b38K84D3hRPzYZD';
                break;
            // LBC Express Inc
            case '107092956014139':
                return 'EAALpK7eInSoBADN9b6ASjHXf2IZB0KFGk4C3wcOZALllsyZBZCZCvztDP3hQuiGV0hekQ6Wrs8rLZAV40kGDDmrUMzEMLsFgNS25UyNAInZCFjt9ZBTUINzF7Wb1MZB3L0BkDBnw89TRBlRRG4YYv3yWNXl0IoT1XRhLdba0qvwqfZAoi94QzsUDhhenZCTymNjtskZD';
                break;
        }
    }

        // private function getPageId($page_entries) {
    //     // $page_entries = json_decode(json_encode($request->all(), true));
    //     // Storage::append('public/page_activity.txt', json_encode($request, JSON_PRETTY_PRINT));
    //     foreach ($page_entries as $page_entry) {
    //         foreach ($page_entry->entry as $entry) {
    //             Storage::append('public/page_activity.txt', $entry->id);
    //         }
    //     }
    // }

}
