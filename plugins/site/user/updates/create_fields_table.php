<?php namespace Site\User\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('site_user_fields', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_user_fields');
    }
}
