<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            $table->string('company_name', 255);
            $table->string('company_name_en', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('business_license', 100)->nullable();   
            $table->string('travel_license', 100)->nullable();     
            $table->year('established_year')->nullable();          
            $table->longText('description')->nullable();           

            $table->string('email', 150)->nullable();
            $table->string('email_support', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('hotline', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('website', 255)->nullable();

            $table->string('address', 500)->nullable();
            $table->string('ward', 150)->nullable();               
            $table->string('district', 150)->nullable();           
            $table->string('province', 150)->nullable();           
            $table->string('country', 100)->nullable()->default('Việt Nam');
            $table->string('google_map_url', 1000)->nullable();    
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('facebook', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->string('youtube', 500)->nullable();
            $table->string('tiktok', 500)->nullable();
            $table->string('zalo', 50)->nullable();

            $table->string('bank_name', 150)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 150)->nullable();
            $table->string('bank_qr_code', 255)->nullable();       

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('og_image', 255)->nullable();

            $table->text('header_scripts')->nullable();
            $table->text('footer_scripts')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
