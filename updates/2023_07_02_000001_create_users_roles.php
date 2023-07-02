<?php

use Illuminate\Support\Facades\Schema;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_roles', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->integer('user_role_id')->nullable();
            $table->index([
                'user_id',
                'user_role_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_roles');
    }
};
