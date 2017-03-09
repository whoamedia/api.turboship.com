<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RolePermission', function (Blueprint $table)
        {
            $table->integer('roleId')->unsigned()->index();
            $table->foreign('roleId')->references('id')->on('Role');


            $table->integer('permissionId')->unsigned()->index();
            $table->foreign('permissionId')->references('id')->on('Permission');

            $table->primary(['roleId', 'permissionId']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('RolePermission');
    }
}
