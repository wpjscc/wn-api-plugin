<?php namespace Wpjscc\Api\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateWpjsccApiTests extends Migration
{
    public function up()
    {
        Schema::create('wpjscc_api_tests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wpjscc_api_tests');
    }
}
