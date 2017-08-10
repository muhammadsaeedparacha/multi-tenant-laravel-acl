<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;

class CreatePermissionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('permissionables', function (Blueprint $table) {
            $db = config()->get('database.connections.master.database');

            $table->increments('id');
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on(new Expression($db . '.permissions'))->onDelete('cascade');

            // $table->integer('user_id')->unsigned()->index()->nullable();
            // $table->foreign('user_id')->references('id')->on(new Expression($db . '.users'))->onDelete('cascade');
            
            $table->boolean('owner')->default(false);
            $table->boolean('manager')->default(false);
            // Whether for Role or User
            $table->integer('permissionable_id');
            $table->string('permissionable_type');

            // Attaching Categories from every module table
            $table->boolean('categories')->default(false);
            $table->integer('categoriable_id')->nullable();
            $table->string('categoriable_type')->nullable();

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
        Schema::connection('tenant')->drop('permissionables');
    }
}