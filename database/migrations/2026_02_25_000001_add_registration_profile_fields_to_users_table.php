<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 120)->nullable()->after('name');
            $table->string('last_name', 120)->nullable()->after('first_name');
            $table->string('country', 80)->nullable()->after('ruc');
            $table->string('city', 120)->nullable()->after('country');
            $table->string('document_type', 20)->nullable()->after('city');
            $table->string('document_number', 30)->nullable()->after('document_type');
            $table->string('gender', 20)->nullable()->after('document_number');
            $table->string('organization_name')->nullable()->after('gender');
            $table->string('organization_address')->nullable()->after('organization_name');
            $table->boolean('marketing_consent')->default(false)->after('organization_address');
            $table->timestamp('terms_accepted_at')->nullable()->after('marketing_consent');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'country',
                'city',
                'document_type',
                'document_number',
                'gender',
                'organization_name',
                'organization_address',
                'marketing_consent',
                'terms_accepted_at',
            ]);
        });
    }
};

