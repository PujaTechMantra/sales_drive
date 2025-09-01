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
        Schema::table('slot_bookings', function (Blueprint $table) {
            //
            $table->tinyInteger('training_done')->default(1)->comment('1: active | 0: inactive')->after('site_ready');
            $table->text('training_remarks')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_bookings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'training_done', 'training_remarks'
            ]);
        });
    }
};
