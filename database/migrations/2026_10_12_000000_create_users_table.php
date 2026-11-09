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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('user_name')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('designation')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('photo')->nullable();
            $table->string('experience_level')->nullable();
            $table->text('skills')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('district_id')->nullable()->constrained();
            $table->foreignId('city_id')->nullable()->constrained();
            $table->string('resume')->nullable();
            $table->text('bio')->nullable();
            $table->string('timezone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->enum('status',['pending','active','inactive','suspended','banned'])->default('pending');
            $table->timestamp('banned_at')->nullable();
            $table->unsignedInteger('banned_count')->default('0');
            $table->timestamp('inactive_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
