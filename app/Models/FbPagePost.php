<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbPagePost extends Model
{
    protected $table = 'fb_page_posts';

    protected $fillable = ['post_id', 'details'];
}
