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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('number')->nullable()->comment('номер заявки');
            $table->uuid('status_uuid')->nullable()->comment('идентификатор статуса заявки');
            $table->date('date')->comment('дата заявки');
            $table->timestamp('time_from');
            $table->timestamp('time_to');
            $table->uuid('request_reasons_uuid')->comment('идентификатор причины заявки');
            $table->string('request_reasons_name')->nullable()->comment('наименование причины заявки');
            $table->integer('passenger_count')->comment('кол-во пассажиров, которые поедут');
            $table->integer('driver_uuid')->nullable()->comment('идентификатор водителя');
            $table->string('driver_name')->nullable()->comment('ФИО водителя');
            $table->uuid('client_uuid')->nullable()->comment('идентификатор пассажира');
            $table->string('client_name')->nullable()->comment('ФИО пассажира');
            $table->string('phone_number')->nullable();
            $table->boolean('for_me')->nullable();
            $table->string('other_client_surname')->nullable()->comment('фамилия');
            $table->string('other_client_name')->nullable()->comment('имя');
            $table->string('other_client_phone')->nullable()->comment('контактный номер телефона');
            $table->uuid('vehicle_uuid')->nullable()->comment('идентификатор ТС');
            $table->string('state_number')->nullable()->comment('гос.номер');
            $table->boolean('possibility_application')->nullable();
            $table->boolean('out_in_base')->nullable();

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
        Schema::dropIfExists('applications');
    }
};
