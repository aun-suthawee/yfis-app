<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make email nullable for YFIS users who use username
            $table->string('email')->nullable()->change();
            
            // Add username for YFIS users
            $table->string('username')->unique()->nullable()->after('name');
            
            // Add contact information
            $table->text('address')->nullable()->after('email');
            $table->string('tel', 20)->nullable()->after('address');
            
            // Add affiliation foreign key for YFIS users
            $table->foreignId('affiliation_id')
                ->nullable()
                ->after('tel')
                ->constrained('affiliations')
                ->onDelete('set null');
            
            $table->index('affiliation_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['affiliation_id']);
            $table->dropIndex(['affiliation_id']);
            $table->dropColumn(['username', 'address', 'tel', 'affiliation_id']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
