<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToFbWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fb_webhooks', function (Blueprint $table) {
            $table->string('contact_id', 100)->nullable()->after('id');
            $table->unsignedTinyInteger('comment_reply_status')->default(0)->after('organization');
            $table->string('comment_reply_id', 100)->nullable()->after('comment_reply_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fb_webhooks', function (Blueprint $table) {
            $table->dropColumn('contact_id');
            $table->dropColumn('comment_reply_status');
            $table->dropColumn('comment_reply_id');
        });
    }
}
