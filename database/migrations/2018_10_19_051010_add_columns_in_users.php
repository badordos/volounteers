<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('full_name')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('city_id')->index()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('education')->nullable();
            $table->text('hobbies')->nullable(); //json
            //skills - отдельная сущность
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
