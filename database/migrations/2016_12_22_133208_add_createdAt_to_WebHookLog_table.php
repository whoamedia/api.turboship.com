<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedAtToWebHookLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('WebHookLog', function (Blueprint $table)
        {
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index()
                ->after('incomingMessage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('WebHookLog', function (Blueprint $table)
        {
            $table->dropColumn('incomingMessage');
        });
    }
}
