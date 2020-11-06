<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    public $timestamps = false;

    public function fb_webhook() {
      return $this->belongsTo('App\Models\FbWebhook');
    }
}
