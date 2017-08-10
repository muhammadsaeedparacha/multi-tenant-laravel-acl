<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('registered_name')->nullable();
            $table->string('logo')->default('default_company_logo.png');
            $table->string('acronym')->nullable();
            $table->string('map_location')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->string('contact')->nullable();
            $table->string('ntn')->nullable();
            $table->string('stn')->nullable();

            $table->string('db_connection')->default('mysql');
            $table->string('db_host')->default(env('DB_HOST', 'localhost'));
            $table->string('db_port')->default('3306');
            $table->string('subdomain')->unique();
            $table->string('db_database')->nullable();
            // $table->string('db_username');
            // $table->string('db_password');

            $table->timestamps();
            $table->softDeletes();
            
            //still needs refinement
            //need to create environemnt variable to assign hashed database name
        });

        Schema::create('company_user', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('settings')->nullable();
            $table->index(['company_id', 'user_id']);
            $table->unique(['company_id', 'user_id']);
            
            $table->boolean('authorized')->default(true);
            
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
        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }
}