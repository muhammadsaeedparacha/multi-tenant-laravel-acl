<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Query\Expression;

class CreateCompanyUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('company_user_role', function (Blueprint $table) {
            $db = config()->get('database.connections.master.database');

            $table->increments('id');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('company_user_id')->unsigned()->index();
            $table->foreign('company_user_id')->references('id')->on(new Expression($db . '.company_user'))->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->drop('role_user');
    }
}
