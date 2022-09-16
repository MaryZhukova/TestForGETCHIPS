<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert
        ([
            'name' => 'user_1@example.com',
            'email' => 'user_1@example.com',
            'password' => bcrypt('password_1'),
        ]);

        DB::table('users')->insert
        ([
            'name' => 'user_2@example.com',
            'email' => 'user_2@example.com',
            'password' => bcrypt('password_2'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
};
