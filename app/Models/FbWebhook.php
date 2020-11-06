<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbWebhook extends Model
{
    protected $table = 'fb_webhooks';

    public function fb_page() {
      return $this->hasOne('App\Models\FbPage', 'page_id', 'page_id');
    }
}
