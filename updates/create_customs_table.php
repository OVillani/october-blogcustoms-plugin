<?php 

namespace Synder\BlogCustoms;

use Schema;
use October\Rain\Database\Updates\Migration;
use October\Rain\Database\Schema\Blueprint;


class CreateCustomsTable extends Migration 
{

    /**
     * Install
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synder_blogcustoms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('post_id')->unsigned();
            $table->string('name');
            $table->enum('type', ['text', 'number', 'array'])->default('text');
            $table->string('value')->nullable();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('rainlab_blog_posts')->onDelete('cascade');
            $table->unique(['post_id', 'name']);
        });
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('synder_blogcustoms');
    }

}