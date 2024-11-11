<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOthersToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('post_code')->after('password')->nullable();
            $table->string('address')->after('post_code')->nullable();
            $table->string('building')->after('address')->nullable();
            $table->string('image_path')->after('building')->nullable();
            $table->boolean('first_login')->default(true)->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('post_code')->after('password')->nullable(false);
            $table->string('address')->after('post_code')->nullable(false);
            $table->string('building')->after('address')->nullable(false);
            $table->string('image_path')->after('building')->nullable(false);
            $table->boolean('first_login')->default(true)->after('image_path');
        });
    }
}
