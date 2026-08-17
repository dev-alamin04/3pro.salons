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
        Schema::table('user_skills', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('note');
            $table->string('status')->default('in_progress')->after('progress')->comment('in_progress,completed');
            $table->foreignId('assigned_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('salon_id')->nullable()->after('assigned_by')->constrained('salons')->nullOnDelete();
            $table->string('skill_type')->default('personal')->after('assigned_by')->comment('personal,assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_skills', function (Blueprint $table) {
            $table->dropColumn(['progress', 'status', 'skill_type', 'assigned_by', 'salon_id']);
            $table->dropForeign(['assigned_by', 'salon_id']);
        });
    }
};
