<?php

namespace App\Services\LBC;

use App\Models\FbWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FbPageService {
    public function __construct() {
        $this->page_id = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc-tst.processes.quandago.app/api/']);
        // $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://dti-tst.processes.quandago.dev/api/']);
    }

    public function receiveTestPageEntryEvent($page_entries) {
        $process_runner_token = '';

        foreach ($page_entries as $page_entry) {
            foreach ($page_entries->entry as $entry) {
                $this->page_id = $entry->id;

                foreach ($entry->changes as $change) {
                    // If comment is made by users
                    if ($change->value->item === 'comment' && $change->value->verb === "add") {
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
                                // 'Audience' => 'https://dti-tst.processes.quandago.dev'
                                'Audience' => 'https://lbc-tst.processes.quandago.app'
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
                                'Type' => 'K',
                                'RelatePath' => 'cCustomer:106',
                                'created_time' => $change->value->created_time,
                                'name' => $change->value->from->id,
                                'message' => $change->value->message,
                                'comment_id' => $change->value->comment_id,
                                'page_id' => $this->page_id,
                                'post_id' => $change->value->post_id
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
                        
                        // Insert page entry to DB
                        $page_entry = new FbWebhook;
                        $page_entry->page_id = $this->page_id;
                        $page_entry->post_id = $change->value->post_id;
                        $page_entry->comment_id = $change->value->comment_id;
                        $page_entry->user_id = $change->value->from->id;
                        $page_entry->name = $change->value->from->name;
                        $page_entry->message = $change->value->message;
                        $page_entry->created_time = $change->value->created_time;
                        if ($create_process_sc == 200) {
                            $page_entry->status = 1;
                        }
                        $page_entry->organization = $this->getTenantName($this->page_id);
                        $page_entry->save();
                        
                        // else {
                        //     return response()->json([
                        //         'message' => 'An unexpected error has occured (Process).'
                        //     ], 500);
                        // }

                        return true;
                    }
                }   
            }
        }
    }

    public function receivePageEntryEvent($page_entries) {
        foreach ($page_entries as $page_entry) {
            foreach ($page_entries->entry as $entry) {
                $this->page_id = $entry->id;

                foreach ($entry->changes as $change) {
                    // If comment is made by users
                    if ($change->value->item === 'comment' && $change->value->verb === "add" && $change->value->from->id !== $this->page_id) {
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
                        Storage::append('public/comments.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
                        'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
                        'post_id: ' . $change->value->post_id . PHP_EOL .
                        'comment_id: ' . $change->value->comment_id . PHP_EOL .
                        'parent_id: ' . $change->value->parent_id . PHP_EOL .
                        'created_time: ' . $change->value->created_time . PHP_EOL .
                        'from.id: ' . $change->value->from->id . PHP_EOL .
                        'from.name: ' . $change->value->from->name . PHP_EOL .
                        'message: ' . $change->value->message . PHP_EOL);

                        return true;
                    }
                }   
            }
        }
    }
    
    public function handleCommentReplyRequest($request) {
        $create_comment_reply_validator = Validator::make($request->all(), [
            'message' => 'required',
            'page_id' => [
                'required',
                // Check if page_id is valid
                function ($attribute, $value, $fail) {
                    $access_token_check = $this->getPageAccessToken($value);

                    if (!isset($access_token_check)) {
                        $fail('The '. $attribute.' parameter provided is either invalid or the page does not exist.');
                    }
                },
            ],
            'comment_id' => [
                'required',
                // Check if comment_id is valid
                function ($attribute, $value, $fail) use ($request) {
                    $comment_id = $request->input('comment_id');
                    $this->access_token = $this->getPageAccessToken($request->input('page_id'));

                    if (!isset($this->access_token)) {
                        $fail('No access token has been found for accessing this comment.');
                    } else {
                        $check_comment_id_request = $this->client->get($comment_id.'?access_token='.$this->access_token, ['http_errors' => false]);
                        $check_comment_id_sc = $check_comment_id_request->getStatusCode();
    
                        if ($check_comment_id_sc !== 200) {
                            $check_comment_id_response = json_decode($check_comment_id_request->getBody()->getContents());
                            $fail($check_comment_id_response->error->message);
                        }
                    }
                },
            ]
        ],
        [
            'message.required' => 'The message parameter is required.',
            'page_id.required' => 'The page_id parameter is required.',
            'comment_id.required' => 'The comment_id parameter is required.',
        ]);

        if ($create_comment_reply_validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ", $create_comment_reply_validator->errors()->all())
            ], 500);
        }

        // Create comment reply
        $message = $request->input('message');
        $comment_id = $request->input('comment_id');
        $this->access_token = $this->getPageAccessToken($request->input('page_id'));

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
            'success' => false,
            'message' => 'An unexpected error has occured'
        ], 500);
    }

    public function handleHideCommentRequest($request) {
        $create_comment_reply_validator = Validator::make($request->all(), [
            'page_id' => [
                'required',
                // Check if page_id is valid
                function ($attribute, $value, $fail) {
                    $access_token_check = $this->getPageAccessToken($value);

                    if (!isset($access_token_check)) {
                        $fail('The '. $attribute.' parameter provided is either invalid or the page does not exist.');
                    }
                },
            ],
            'comment_id' => [
                'required',
                // Check if comment_id is valid
                function ($attribute, $value, $fail) use ($request) {
                    $comment_id = $request->input('comment_id');
                    $this->access_token = $this->getPageAccessToken($request->input('page_id'));

                    if (!isset($this->access_token)) {
                        $fail('No access token has been found for accessing this comment.');
                    } else {
                        $check_comment_id_request = $this->client->get($comment_id.'?access_token='.$this->access_token, ['http_errors' => false]);
                        $check_comment_id_sc = $check_comment_id_request->getStatusCode();
    
                        if ($check_comment_id_sc !== 200) {
                            $check_comment_id_response = json_decode($check_comment_id_request->getBody()->getContents());
                            $fail($check_comment_id_response->error->message);
                        }
                    }
                },
            ]
        ],
        [
            'page_id.required' => 'The page_id parameter is required.',
            'comment_id.required' => 'The comment_id parameter is required.',
        ]);

        if ($create_comment_reply_validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ", $create_comment_reply_validator->errors()->all())
            ], 500);
        }
        
        // Process comment to be hidden
        $comment_id = $request->input('comment_id');
        $this->access_token = $this->getPageAccessToken($request->input('page_id'));

        $hide_comment_request = $this->client->post($comment_id,
            ['http_errors' => false], 
            ['json' => 
                [
                    'is_hidden' => 'true',
                    'access_token' => $this->access_token
                ]
            ]
        );
        $hide_comment_sc = $hide_comment_request->getStatusCode();

        if ($hide_comment_sc == 200) {
            $hide_comment = json_decode($hide_comment_request->getBody()->getContents());            
            return response()->json($hide_comment, 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'The comment_id specified has already been hidden.'
        ], 500);
    }

    private function getPageAccessToken($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return env('TRITEL_API_TOKEN');
                break;
            // LBC Express Inc
            case '107092956014139':
                return env('LBC_EXPRESS_INC_TOKEN');
                break;
        }
    }

    private function getTenantName($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return 'Tritel';
                break;
            // LBC Express Inc
            case '107092956014139':
                return 'LBC';
                break;
        }
    }
}
