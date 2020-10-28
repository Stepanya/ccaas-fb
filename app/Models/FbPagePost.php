<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPagePost extends Model
{
    protected $table = 'sandbox_fb_page_posts';

    protected $fillable = ['post_id', 'details'];
}
