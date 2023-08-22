<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wpjscc_api_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('token_id')->comment('token id');
            $table->char('name', 100)->comment('服务名称');
            $table->char('code', 100)->comment('服务编码');
            $table->softDeletes();
            $table->index('token_id', 'idx_token_id');
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
        Schema::dropIfExists('wpjscc_api_services');
    }
};
