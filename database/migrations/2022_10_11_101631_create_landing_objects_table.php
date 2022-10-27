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
        Schema::create('landing_objects', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('user_uuid')->nullable()->comment('идентификатор пользователя');
            $table->string('name')->nullable()->comment('наименование объекта');
            $table->string('actual_address')->nullable()->comment('адрес');
            $table->uuid('communal_municipality_uuid')->nullable()->comment('муниципальное образование');
            $table->float('latitude')->nullable()->comment('широта');
            $table->float('longitude')->nullable()->comment('долгота');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landing_objects');
    }
};
