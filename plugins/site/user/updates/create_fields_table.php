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
            $table->string('name');
            $table->string('ident')->unique();
            $table->boolean('required')->default(false);
            $table->boolean('enabled')->default(true);
            $table->boolean('sort_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_user_fields');
    }
}
