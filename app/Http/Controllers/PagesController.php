<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FbWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function index() {
        return view('index');
    }

    public function dashboard() {
        $webhook_entries = FbWebhook::from('fb_webhooks as wh1')
          ->select('page_id', DB::raw('count(id) as contacts_created, (select count(id) from fb_webhooks wh2 where wh2.page_id = wh1.page_id AND `status` = 0) as failed_entries'))
          ->where('status', 1)
          ->groupBy('page_id')
          ->orderBy('contacts_created', 'desc')
          ->get();

        // $failed_entries = FbWebhook::selectRaw('page_id, count(id) as failed_contacts')
        //   ->where('status', 0)
        //   ->groupBy('page_id')
        //   ->orderBy('failed_contacts', 'desc')
        //   ->get();

        return view('dashboard')->with('entries', $webhook_entries);
    }
}
