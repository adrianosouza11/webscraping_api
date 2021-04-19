<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSelectorCollectionDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selector_collection_default', function (Blueprint $table) {
            $table->id();
            $table->string('domain_url', 255)->unique();
            $table->string('name', 45);
            $table->json('collection_selector_json');
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selector_collection_default');
    }
}
