<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('รหัสโรงเรียน');
            $table->string('province')->comment('จังหวัด');
            $table->string('district')->comment('อำเภอ');
            $table->string('name')->comment('ชื่อโรงเรียน');
            $table->foreignId('affiliation_id')
                ->constrained('affiliations')
                ->onDelete('cascade')
                ->comment('สังกัด');
            $table->timestamps();

            $table->index('affiliation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
