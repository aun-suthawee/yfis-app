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
        Schema::table('shelters', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('affiliation_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->enum('status', ['open', 'closed'])->default('closed');
            $table->integer('current_occupancy')->default(0);
            $table->boolean('is_kitchen')->default(false);
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropForeign(['affiliation_id']);
            $table->dropColumn(['district_id', 'affiliation_id', 'status', 'current_occupancy', 'is_kitchen', 'contact_name', 'contact_phone']);
        });
    }
};
