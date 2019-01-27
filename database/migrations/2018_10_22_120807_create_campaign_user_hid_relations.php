<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignUserHidRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_user_hid', function (Blueprint $table) {
            $table->unsignedInteger('campaign_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->text('text_reason');
            $table->timestamps();

            $table->primary(['campaign_id', 'user_id']);

            $table->foreign('campaign_id')->references('id')->on('campaigns')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_user_hid');
    }
}
