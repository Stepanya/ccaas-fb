<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostIdToSandboxFbPagePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sandbox_fb_page_posts', function (Blueprint $table) {
            $table->string('post_id', 100)->after('page_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sandbox_fb_page_posts', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });
    }
}
