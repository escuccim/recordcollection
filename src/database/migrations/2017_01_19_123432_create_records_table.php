<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(config('records.table_name'))) {
            Schema::create(config('records.table_name'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('artist');
                $table->string('title');
                $table->string('label');
                $table->string('catalog_no');
                $table->string('style')->nullable();
                $table->text('notes')->nullable();
                $table->string('discogs')->nullable();
                $table->string('thumb')->nullable();
                $table->integer('discogs_results')->nullable();
                $table->timestamps();

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('records.table_name'));
    }
}
