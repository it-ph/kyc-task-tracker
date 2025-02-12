<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->id();
            // $table->string('emp_id');
            // $table->string('email')->unique();
            // $table->string('emp_code')->nullable();
            // $table->string('fullname');
            // $table->string('last_name');
            // $table->string('position')->nullable();
            // $table->string('date_hired')->nullable();
            // $table->string('employment_status');
            // $table->string('password')->nullable();
            // $table->rememberToken();
            // $table->timestamps();

            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->integer('cluster_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('tl_id')->nullable();
            $table->integer('om_id')->nullable();
            $table->integer('role_id')->nullable(); // Analyst, Senior Analyst, etc.
            $table->string('permission'); // Agent, Team Lead, Operations Manager, Admin
            $table->datetime('shift_date')->nullable();
            $table->string('status');
            $table->rememberToken();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
