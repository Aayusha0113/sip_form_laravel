<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 255)->nullable();
            $table->string('customer_type', 255)->nullable();
            $table->string('sip_type', 255)->nullable();
            $table->integer('sessions')->nullable();
            $table->integer('did')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('name_of_proprietor', 255)->nullable();
            $table->string('company_reg_no', 100)->nullable();
            $table->date('reg_date')->nullable();
            $table->string('pan_no', 50)->nullable();
            $table->string('province_perm', 100)->nullable();
            $table->string('district_perm', 100)->nullable();
            $table->string('municipality_perm', 100)->nullable();
            $table->string('ward_perm', 50)->nullable();
            $table->string('tole_perm', 100)->nullable();
            $table->string('province_install', 100)->nullable();
            $table->string('district_install', 100)->nullable();
            $table->string('municipality_install', 100)->nullable();
            $table->string('ward_install', 50)->nullable();
            $table->string('tole_install', 100)->nullable();
            $table->string('landline', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 150)->nullable();
            $table->text('objectives')->nullable();
            $table->text('purpose')->nullable();
            $table->string('authorized_signature', 255)->nullable();
            $table->string('signature_name', 255)->nullable();
            $table->string('position', 100)->nullable();
            $table->date('signature_date')->nullable();
            $table->string('seal', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
