<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FbLbcRegion;
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
        $fb_lbc_regions = FbLbcRegion::all();

        return view('dashboard')->with('regions', $fb_lbc_regions);
    }

    public function getFilteredEntries(Request $request, PagesService $pagesService) {
        $filtered_entries_response = $pagesService->handleGetFilteredEntriesEvent($request);

        return $filtered_entries_response;
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
