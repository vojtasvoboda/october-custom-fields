<?php namespace Site\User\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('site_user_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('ident')->unique();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_user_users_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->primary(['user_id', 'group_id'], 'user_groups');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_user_groups');
        Schema::dropIfExists('site_user_users_groups');
    }
}
