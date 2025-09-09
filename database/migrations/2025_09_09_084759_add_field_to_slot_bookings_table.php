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
            $table->string('distributor_code')->nullable()->after('training_done');
            $table->string('city')->nullable()->after('distributor_email');
            $table->string('state')->nullable()->after('city');
            $table->string('zone')->nullable()->after('state');
            $table->string('distributor_contact_person')->nullable()->after('pan_number');
            $table->string('distributor_contact_person_phone')->nullable()->after('distributor_contact_person');
            $table->string('so_name')->nullable()->after('distributor_contact_person_phone');
            $table->string('so_contact_no')->nullable()->after('so_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_bookings', function (Blueprint $table) {
            //
            $table->dropDown([
                'ditributor_code', 'city', 'state', 'zone', 'ditributor_contact_person', 
                'ditributor_contact_person_phone', 'so_name', 'so_contact_no'
            ]);
        });
    }
};
