<?php

namespace App\Services\Sandbox\LBC;

use App\Models\FbWebhook;
use App\Models\FbPagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FbPageService {
    public function __construct() {
        $this->page_id = '';
        $this->access_token = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc-acc.processes.quandago.app/api/']);
    }

    public function receiveTestPageEntryEvent($page_entries) {
        $process_runner_token = '';

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
                        
                        // Get post details
                        $page_post_details = $this->getPagePostDetails($change->value->post_id, $this->page_id);

                        // $date->setTimestamp($change->value->created_time);
                        // Storage::append('public/comments.txt', date('Y-m-d H:i:s', $change->value->created_time) . PHP_EOL . 
                        Storage::append('public/comments_tritel_user.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
                        'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL . 
                        'post_id: ' . $change->value->post_id . PHP_EOL .
                        'post_content: ' . $page_post_details . PHP_EOL .
                        'comment_id: ' . $change->value->comment_id . PHP_EOL .
                        'parent_id: ' . $change->value->parent_id . PHP_EOL .
                        'created_time: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL .
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
                                // 'Audience' => 'https://lbc-tst.processes.quandago.app'
                                'Audience' => 'https://lbc-acc.processes.quandago.app'
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
                                'Assignee' => 'CS Facebook Comments',
                                'Priority' => 'M',
                                'Type' => 'L',
                                'RelatePath' => 'Anonymous:' . $change->value->from->id,
                                'FormData' => [
                                    'created_time' => $created_at_datetime->format('Y-m-d H:i:s'),
                                    'name' => $change->value->from->name,
                                    'customer_facebook_id' => $change->value->from->id,
                                    'message' => $change->value->message,
                                    'comment_id' => $change->value->comment_id,
                                    'page_id' => $this->page_id,
                                    'post_id' => $change->value->post_id,
                                    'post_content' => $page_post_details
                                ]
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
                    // Check if a status is made by the page 
                    } else if ($change->value->item === 'status' && $change->value->verb === 'add') {
                        // Storage::append('public/page_activity.txt', json_encode($page_entries, JSON_PRETTY_PRINT));
                        // return true;
                        $page_post = new FbPagePost;
                        $page_post->post_id = $change->value->post_id;
                        $page_post->details = $change->value->message;
                        $page_post->save();

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
                    // Temporarily block comments from LBC Express PH
                    // && $this->page_id !== '107092956014139' 
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

                        // Insert page entry to DB
                        $page_entry = new FbWebhook;
                        $page_entry->page_id = $this->page_id;
                        $page_entry->post_id = $change->value->post_id;
                        $page_entry->comment_id = $change->value->comment_id;
                        $page_entry->user_id = $change->value->from->id;
                        $page_entry->name = $change->value->from->name;
                        $page_entry->message = $change->value->message;
                        $page_entry->created_time = $change->value->created_time;
                        // if ($create_process_sc == 200) {
                            $page_entry->status = 1;
                        // }
                        $page_entry->organization = $this->getTenantName($this->page_id);
                        $page_entry->save();

                        return true;
                    }
                }   
            }
        }
    }
    
    public function handleCommentReplyRequest($request) {
        // Create comment reply
        $message = $request->message;
        $comment_id = $request->comment_id;
        $this->access_token = $this->getPageAccessToken($request->page_id);

        $reply_to_comment_request = $this->client->post($comment_id.'/comments', [
            'json' => [
                'message' => $message,
                'access_token' => $this->access_token
            ]
        ]);
        $reply_to_comment_sc = $reply_to_comment_request->getStatusCode();

        if ($reply_to_comment_sc == 200) {
            $reply_to_comment = json_decode($reply_to_comment_request->getBody()->getContents());            

            $data = array();
            $data['success'] = true;
            $data['message'] = 'Comment created successfully. ID: ' . $reply_to_comment->id;
            $data['status_code'] = 200;
            return $data;
        }

        $data = array();
        $data['success'] = false;
        $data['message'] = 'An unexpected error has occured.';
        $data['status_code'] = 500;
        return $data;
    }

    public function handleHideCommentRequest($request) {
        // Process comment to be hidden
        $comment_id = $request->comment_id;
        $this->access_token = $this->getPageAccessToken($request->page_id);

        $hide_comment_request = $this->client->post($comment_id, [
            'json' => [
                'is_hidden' => 'true',
                'access_token' => $this->access_token
            ],
            'http_errors' => false
        ]);

        $hide_comment_sc = $hide_comment_request->getStatusCode();

        if ($hide_comment_sc == 200) {
            $hide_comment = json_decode($hide_comment_request->getBody()->getContents());            

            $data = array();
            $data['success'] = true;
            $data['message'] = 'Comment hidden successfully.';
            $data['status_code'] = 200;
            return $data;
        }

        // Get error message
        $hide_comment_err_response = json_decode($hide_comment_request->getBody()->getContents());

        $data = array();
        $data['success'] = false;
        $data['message'] = $hide_comment_err_response->error->error_user_msg;
        $data['status_code'] = 500;
        return $data;
    }

    private function getPagePostDetails($post_id, $page_id) {
        // If no posts are found related to post_id, send GET request to Graph API post endpoint
        $cache_key = 'post_id_' . $post_id;
        $cached_time = 60 * 60 * 24 * 7; // 1 week

        $cached_post = Cache::remember($cache_key, $cached_time, function () use ($post_id, $page_id) {
            $page_post = FbPagePost::where('post_id', $post_id)->firstOr(function() use ($post_id, $page_id) {
                $this->access_token = $this->getPageAccessToken($page_id);
                $find_page_post_request = $this->client->get($post_id.'?access_token='.$this->access_token);
                $find_page_post_sc = $find_page_post_request->getStatusCode();
    
                if ($find_page_post_sc == 200) {
                    $page_post = json_decode($find_page_post_request->getBody()->getContents());
                    $page_post_details = $page_post->message;                            
                    return FbPagePost::create([
                        'post_id' => $post_id,
                        'details' => $page_post->message,
                    ]);
                }
            });

            return $page_post->details;
        });
        
        return $cached_post;
    }

    private function getPageAccessToken($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return env('TRITEL_API_TOKEN');
                break;
            // LBC Express Inc (PH)
            case '107092956014139':
                return env('LBC_EXPRESS_INC_PH_TOKEN');
                break;
            // North America (NA)
            // LBC Express Inc. (Canada)
            case '767635103382140':
                return env('LBC_EXPRESS_INC_CA_TOKEN');
                break;
            // LBC Express Inc. (US)
            case '1625673577698440':
                return env('LBC_EXPRESS_INC_US_TOKEN');
                break;
        }
    }

    private function getTenantName($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return 'Tritel';
                break;
            // LBC Express Inc (PH)
            case '107092956014139':
            // North America (NA)
            // LBC Express Inc. (Canada)
            case '767635103382140':
            // LBC Express Inc. (US)
            case '1625673577698440':
                return 'LBC';
                break;
        }
    }
}
