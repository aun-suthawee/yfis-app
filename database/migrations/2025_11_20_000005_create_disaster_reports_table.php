<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disaster_reports', function (Blueprint $table) {
            $table->id();
            $table->dateTime('reported_at');
            $table->string('disaster_type');
            $table->string('organization_name');
            $table->foreignId('district_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('affiliation_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('current_status');
            $table->boolean('is_published')->default(false);
            $table->enum('teaching_status', ['open', 'closed']);
            $table->unsignedInteger('affected_students')->default(0);
            $table->unsignedInteger('injured_students')->default(0);
            $table->unsignedInteger('dead_students')->default(0);
            $table->text('dead_students_list')->nullable();
            $table->unsignedInteger('affected_staff')->default(0);
            $table->unsignedInteger('injured_staff')->default(0);
            $table->unsignedInteger('dead_staff')->default(0);
            $table->text('dead_staff_list')->nullable();
            $table->decimal('damage_building', 12, 2)->default(0);
            $table->decimal('damage_equipment', 12, 2)->default(0);
            $table->decimal('damage_material', 12, 2)->default(0);
            $table->decimal('damage_total_request', 12, 2)->default(0);
            $table->text('assistance_received')->nullable();
            $table->string('contact_name');
            $table->string('contact_position');
            $table->string('contact_phone');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('form_hash', 64)->unique();
            $table->timestamps();

            $table->index(['district_id', 'affiliation_id']);
            $table->index('disaster_type');
            $table->index('current_status');
            $table->index('teaching_status');
            $table->index('reported_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_reports');
    }
};
