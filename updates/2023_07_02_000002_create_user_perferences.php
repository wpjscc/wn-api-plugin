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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('namespace', '100');
            $table->string('group', '50');
            $table->string('item', '150');
            $table->text('value');
            $table->index([
                'user_id',
                'namespace',
                'group',
                'item'
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
        Schema::dropIfExists('user_preferences');
    }
};
