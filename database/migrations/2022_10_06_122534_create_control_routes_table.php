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
        Schema::create('control_routes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('application_uuid')->comment('идентификатор заявки');
            $table->uuid('landing_object_uuid')->comment('идентификатор (объекта) точки');
            $table->integer('point_index')->comment('индекс точки');
            $table->timestamp('time_plan')->nullable()->comment('плановое время');
            $table->timestamp('time_fact')->nullable()->comment('фактическое время');

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
        Schema::dropIfExists('control_routes');
    }
};
