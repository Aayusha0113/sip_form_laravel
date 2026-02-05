<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_companies', function (Blueprint $table) {
            $table->id();
            $table->string('file_no', 10)->nullable();
            $table->string('sip_number', 50)->nullable()->index();
            $table->string('DN', 100)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('customer_type', 100)->nullable();
            $table->string('proprietor_name', 100)->nullable();
            $table->string('company_reg_no', 50)->nullable();
            $table->date('reg_date')->nullable();
            $table->string('pan_no', 50)->nullable();
            $table->string('address_perm', 255)->nullable();
            $table->string('address_install', 255)->nullable();
            $table->string('landline', 255)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('sip_type', 255)->nullable();
            $table->integer('sessions')->nullable();
            $table->integer('did')->nullable();
            $table->string('objectives', 255)->nullable();
            $table->string('purpose', 255)->nullable();
            $table->string('authorized_signature', 100)->nullable();
            $table->string('signature_name', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->date('signature_date')->nullable();
            $table->string('seal', 100)->nullable();
            $table->string('perm_province', 100)->nullable();
            $table->string('perm_district', 100)->nullable();
            $table->string('perm_municipality', 100)->nullable();
            $table->string('perm_ward', 20)->nullable();
            $table->string('perm_tole', 100)->nullable();
            $table->string('inst_province', 100)->nullable();
            $table->string('inst_district', 100)->nullable();
            $table->string('inst_municipality', 100)->nullable();
            $table->string('inst_ward', 20)->nullable();
            $table->string('inst_tole', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_companies');
    }
};
