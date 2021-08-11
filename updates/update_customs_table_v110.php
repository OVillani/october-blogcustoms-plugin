<?php 

namespace Synder\BlogCustoms;

use Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;
use October\Rain\Database\Schema\Blueprint;


class UpdateCustomsTableV110 extends Migration 
{
    /**
     * Install
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE synder_blogcustoms CHANGE `type` `type` VARCHAR(64);");
        Schema::table('synder_blogcustoms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned()->nullable()->change();
            $table->integer('order')->nullable()->after('value')->default(0);
        });
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        Schema::table('synder_blogcustoms', function (Blueprint $table) {
            $table->dropColumn('order');   
        });
    }
}
