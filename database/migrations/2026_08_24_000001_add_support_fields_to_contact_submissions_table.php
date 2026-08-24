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
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('name');
            $table->string('salon_name')->nullable()->after('job_title');
            $table->string('city_state')->nullable()->after('salon_name');
            $table->string('attachment')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropColumn(['job_title', 'salon_name', 'city_state', 'attachment']);
        });
    }
};
