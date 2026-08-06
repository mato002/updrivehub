<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('full_name');
            $table->string('national_id');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('phone');
            $table->string('alternative_phone')->nullable();
            $table->string('email');
            $table->string('county');
            $table->string('town');
            $table->text('address');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->string('licence_number');
            $table->string('licence_class');
            $table->date('licence_issue_date');
            $table->date('licence_expiry_date');
            $table->unsignedTinyInteger('years_of_experience');
            $table->json('vehicle_types');
            $table->text('driving_career');
            $table->json('employment_history');
            $table->string('id_front_path');
            $table->string('id_back_path');
            $table->string('selfie_path');
            $table->string('licence_path');
            $table->string('cv_path')->nullable();
            $table->string('good_conduct_path')->nullable();
            $table->string('medical_path')->nullable();
            $table->string('recommendation_path')->nullable();
            $table->string('defensive_driving_path')->nullable();
            $table->string('digital_signature');
            $table->string('status')->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_applications');
    }
};
