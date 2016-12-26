<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopifyWebHookLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShopifyWebHookLog', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('integratedShoppingCartId')->unsigned()->index();
            $table->foreign('integratedShoppingCartId')->references('id')->on('IntegratedShoppingCart');

            //  $table->integer('integrationWebHookId')->unsigned()->index();
            //  $table->foreign('integrationWebHookId')->references('id')->on('IntegrationWebHook');

            $table->string('topic', 20)->index();

            $table->boolean('verified')->index();
            $table->boolean('success')->index();
            $table->integer('entityId')->unsigned()->index()->nullable()->default(null);
            $table->string('externalId')->index()->nullable()->default(null);
            $table->boolean('entityCreated')->index()->nullable()->default(false);
            $table->text('notes')->nullable()->default(null);
            $table->text('errorMessage')->nullable()->default(null);
            $table->text('incomingMessage');
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShopifyWebHookLog');
    }
}
