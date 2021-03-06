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
            $table->string('type')->default('text');
            $table->boolean('required')->default(false);
            $table->boolean('enabled')->default(true);
            $table->boolean('sort_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_user_users_fields', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->string('value')->nullable();
            $table->primary(['user_id', 'field_id'], 'user_fields');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_user_fields');
        Schema::dropIfExists('site_user_users_fields');
    }
}
