<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientIdToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('User', function (Blueprint $table)
        {
            $table->integer('clientId')
                ->unsigned()->index()
                ->nullable()->default(NULL)
                ->after('organizationId');

            $table->foreign('clientId')->references('id')->on('Client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('User', function (Blueprint $table)
        {
            $table->dropForeign('user_clientid_foreign');
            $table->dropColumn('clientId');
        });
    }
}
