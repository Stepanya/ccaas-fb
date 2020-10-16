<?php

namespace App\Services\LBC;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FbPageService {
    public function __construct() {
        $this->page_id = '';
        // $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc-tst.processes.quandago.app/api/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://dti-tst.processes.quandago.dev/api/']);
    }

    public function receivePageEntryEvent($page_entries) {
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

                        return true;
                    }
                }   
            }
        }
    } 
}
