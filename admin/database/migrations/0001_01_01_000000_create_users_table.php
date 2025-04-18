<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->string('signature')->nullable();
            $table->timestamps();
        });

        // Travel Requests Table
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['local', 'overseas']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('intended_departure_date');
            $table->date('intended_return_date');
            $table->text('admin_comment')->nullable();
            $table->timestamps();
        });

        // Travel Request Questions
        Schema::create('travel_request_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });

        // Travel Request Answers
        Schema::create('travel_request_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('travel_request_questions')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->timestamps();
        });

        // Local Travel Forms
        Schema::create('local_travel_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->text('admin_comment')->nullable();
            $table->foreignId('local_supervisor')->nullable()->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        // Local Form Questions
        Schema::create('local_form_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('type', ['text', 'choice'])->default('text');
            $table->boolean('allow_other')->default(false);
            $table->json('choices')->nullable();
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Local Form Answers
        Schema::create('local_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_travel_form_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('local_form_questions')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->timestamps();
        });

        // Overseas Travel Forms
        Schema::create('overseas_travel_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->text('admin_comment')->nullable();
            $table->foreignId('overseas_supervisor')->nullable()->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        // Overseas Form Questions
        Schema::create('overseas_form_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('type', ['text', 'choice'])->default('text');
            $table->boolean('allow_other')->default(false);
            $table->json('choices')->nullable();
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Overseas Form Answers
        Schema::create('overseas_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overseas_travel_form_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('overseas_form_questions')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->timestamps();
        });

        // Form Attachments
        Schema::create('form_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('original_name');
            $table->foreignId('local_travel_form_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('overseas_travel_form_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('form_attachments');
        Schema::dropIfExists('overseas_form_answers');
        Schema::dropIfExists('overseas_form_questions');
        Schema::dropIfExists('overseas_travel_forms');
        Schema::dropIfExists('local_form_answers');
        Schema::dropIfExists('local_form_questions');
        Schema::dropIfExists('local_travel_forms');
        Schema::dropIfExists('travel_request_answers');
        Schema::dropIfExists('travel_request_questions');
        Schema::dropIfExists('travel_requests');
        Schema::dropIfExists('users');
    }
};
