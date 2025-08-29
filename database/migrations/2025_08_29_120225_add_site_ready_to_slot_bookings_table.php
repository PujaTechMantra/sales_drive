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
            $table->tinyInteger('site_ready')->default(1)->comment('1: active | 0: inactive')->after('client_id');
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
                'site_ready'
            ]);
        });
    }
};
