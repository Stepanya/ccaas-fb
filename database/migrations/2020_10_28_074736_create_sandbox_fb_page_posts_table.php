<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSandboxFbPagePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sandbox_fb_page_posts', function (Blueprint $table) {
            $table->id();
            $table->string('page_id', 100);
            $table->string('page_name', 100);
            $table->text('details');
            $table->unsignedInteger('created_time');
            $table->string('organization', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sandbox_fb_page_posts');
    }
}
