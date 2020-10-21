<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSandboxFbWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sandbox_fb_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('page_id', 100);
            $table->string('post_id', 100);
            $table->string('comment_id', 100);
            $table->string('user_id', 100);
            $table->string('name', 100);
            $table->text('message');
            $table->unsignedInteger('created_time');
            $table->unsignedTinyInteger('status')->default(0);	
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
        Schema::dropIfExists('sandbox_fb_webhooks');
    }
}
