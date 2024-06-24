<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_information', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('phone');
            $table->string('IDCard');
            $table->string('licensePlate');
            $table->unsignedBigInteger('area_id');
            $table->integer('numberLocation');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Thiết lập khóa ngoại cho cột area_id
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');

            // Thiết lập ràng buộc duy nhất cho cặp area_id và numberLocation
            $table->unique(['area_id', 'numberLocation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_information', function (Blueprint $table) {
            // Hủy bỏ ràng buộc duy nhất cho cặp area_id và numberLocation
            $table->dropUnique(['area_id', 'numberLocation']);

            // Hủy bỏ khóa ngoại và xóa cột area_id
            $table->dropForeign(['area_id']);
        });

        Schema::dropIfExists('vehicle_information');
    }
}
