<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInSandboxFbPagePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sandbox_fb_page_posts', function (Blueprint $table) {
            $table->dropColumn('page_id');
            $table->dropColumn('page_name');
            $table->dropColumn('created_time');
            $table->dropColumn('organization');
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
            $table->string('page_id', 100);
            $table->string('page_name', 100);
            $table->unsignedInteger('created_time');
            $table->string('organization', 50);
        });
    }
}
