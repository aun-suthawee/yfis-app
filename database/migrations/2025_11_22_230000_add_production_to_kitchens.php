<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->integer('water_bottles')->default(0)->after('facilities')->comment('จำนวนน้ำดื่ม (ขวด)');
            $table->integer('food_boxes')->default(0)->after('water_bottles')->comment('จำนวนอาหาร (กล่อง)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->dropColumn(['water_bottles', 'food_boxes']);
        });
    }
};
