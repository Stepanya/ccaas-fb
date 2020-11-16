<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FbPage;
use App\Models\FbWebhook;
use App\Services\V1\LBC\PagesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function __construct() {
      $this->middleware('auth')->except('index');
    }

    public function index() {
        return view('index');
    }

    public function home() {
        return view('home');
    }

    public function dashboard() {
        $webhook_entries = FbWebhook::from('fb_webhooks as wh1')
          ->select('page_id', DB::raw('count(id) as contacts_created, (select count(id) from fb_webhooks wh2 where wh2.page_id = wh1.page_id AND `status` = 0) as failed_entries'))
          ->where('status', 1)
          ->groupBy('page_id')
          ->orderBy('contacts_created', 'desc')
          ->get();

        return view('dashboard')->with('entries', $webhook_entries);
    }

      public function failedEntries() {
        $fb_pages = FbPage::all();

        return view('failed_entries')->with('pages', $fb_pages);
      // return view('failed_entries')->with('entries', $webhook_entries);
    }

    public function getFailedEntries(Request $request, PagesService $pagesService) {
        $failed_entries_response = $pagesService->handleGetFailedEntriesEvent($request);

        return $failed_entries_response;
    }
}
