<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(\Dashed\DashedPages\Models\Page::withTrashed()->get() as $model){
            $model->site_id = json_encode([$model->site_id]);
            $model->save();
        }

        Schema::table('dashed__pages', function (Blueprint $table) {
            $table->renameColumn('site_id', 'site_ids');
        });

        Schema::table('dashed__pages', function (Blueprint $table) {
            $table->json('site_ids')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('array', function (Blueprint $table) {
            //
        });
    }
};
