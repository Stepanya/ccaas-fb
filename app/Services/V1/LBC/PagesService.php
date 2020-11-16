<?php

namespace App\Services\V1\LBC;

use App\Traits\V1\LBC\FbPageTrait;
use App\Models\FbWebhook;
use App\Models\FbPagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PagesService
{
    use FbPageTrait;

    public function __construct() {
        $this->page_id = '';
        $this->access_token = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        $this->vanad_client = new \GuzzleHttp\Client(['base_uri' => 'https://lbc.processes.quandago.app/api/']);
    }

    public function handleGetFailedEntriesEvent($request) {
        $page_id = $request->input('page_id');
        $created_date = $request->input('created_date');

        $failed_entries = FbWebhook::where('status', 0)
          ->where('page_id', $page_id)
          ->whereDate('created_at', $created_date)
          ->get();

        if ($failed_entries->isEmpty()) {
          return redirect('failed-entries')->with('error', 'No failed entries found.');
        }

        foreach ($failed_entries as $failed_entry) {
          // Initialize ProcessRunnerToken variable
          $process_runner_token = '';

          // Current datetime
          $current_datetime = new \DateTime('now', new \DateTimeZone('Asia/Manila'));

          // Convert created_at value to datetime
          $created_at_datetime = new \DateTime(date('Y-m-d H:i:s', $failed_entry->created_time));

          // Get post details
          $page_post_details = $this->getPagePostDetails($failed_entry->post_id, $failed_entry->page_id);

          Storage::append('public/resent_failed_entries.txt', '[' . $current_datetime->format('Y-m-d H:i:s') . ']' . PHP_EOL .
          'Created Time Converted: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL .
          'post_id: ' . $failed_entry->post_id . PHP_EOL .
          'post_content: ' . $page_post_details . PHP_EOL .
          'comment_id: ' . $failed_entry->comment_id . PHP_EOL .
          'created_time: ' . $created_at_datetime->format('Y-m-d H:i:s') . PHP_EOL .
          'from.id: ' . $failed_entry->user_id . PHP_EOL .
          'from.name: ' . $failed_entry->name . PHP_EOL .
          'message: ' . $failed_entry->message . PHP_EOL);

          // Get ProcessRunnerToken via Authenticate API
          $auth_api_request = $this->vanad_client->post('auth/authenticate/false', [
              'auth' => [
                  env('VANAD_API_USER'), env('VANAD_API_PASS')
              ],
              'json' => [
                  'Audience' => 'https://lbc.processes.quandago.app'
              ],
              'http_errors' => false
          ]);

          $auth_api_sc = $auth_api_request->getStatusCode();
          // If successful, pass ProcessRunnerToken to $process_runner_token variable
          if ($auth_api_sc == 200) {
              $auth_api_response = $auth_api_request->getBody()->getContents();
              $process_runner_token = $auth_api_response;
          } else {
              $auth_api_phrase = $auth_api_request->getReasonPhrase();
              Log::info("Authorization: " . $auth_api_sc . " " . $auth_api_phrase . " Comment ID: " . $failed_entry->comment_id);
              return redirect('failed-entries')->with('error', 'An error has occured with this request. (Authorization: ' . $auth_api_sc . ' ' . $auth_api_phrase);
          }

          // Create Process via Process API
          $create_process_request = $this->vanad_client->post('package/process/contact', [
              'json' => [
                  'Assignee' => $this->getAssigneeValue($failed_entry->page_id),
                  'Priority' => 'M',
                  'Type' => 'L',
                  'RelatePath' => 'Anonymous:' . $failed_entry->user_id,
                  'FormData' => [
                      'created_time' => $created_at_datetime->format('Y-m-d H:i:s'),
                      'name' => $failed_entry->name,
                      'customer_facebook_id' => $failed_entry->user_id,
                      'message' => $failed_entry->message,
                      'comment_id' => $failed_entry->comment_id,
                      'page_id' => $failed_entry->page_id,
                      'post_id' => $failed_entry->post_id,
                      'post_content' => $page_post_details
                  ]
              ],
              'headers' => [
                  'ProcessRunnerToken' => $process_runner_token
              ],
              'http_errors' => false
          ]);

          $create_process_sc = $create_process_request->getStatusCode();
          // If successful, append CaseId and ContactId to text file.
          if ($create_process_sc == 200) {
              $create_process_response = json_decode($create_process_request->getBody()->getContents());
              Storage::append('public/resent_failed_entries.txt',
                  'CaseId: ' . $create_process_response->CaseId . PHP_EOL .
                  'ContactId: ' . $create_process_response->ContactId . PHP_EOL);
          } else {
              $create_process_reason = $create_process_request->getReasonPhrase();
              Log::info("Create Contact: " . $create_process_sc . " " . $create_process_reason . " Comment ID: " . $failed_entry->comment_id);
              return redirect('failed-entries')->with('error', 'An error has occured with this request. (Create Process: ' . $create_process_sc . ' ' . $create_process_reason);
          }

          // Change status value of successfully resent page entries from 0 to 1
          $failed_page_entry = FbWebhook::where('comment_id', $failed_entry->comment_id)->first();
          // Log::info($failed_entry);
          $failed_page_entry->status = 1;
          $failed_page_entry->save();
        }

        return redirect('failed-entries')->with('success', 'All found failed entries have been created successfully.');
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
}
