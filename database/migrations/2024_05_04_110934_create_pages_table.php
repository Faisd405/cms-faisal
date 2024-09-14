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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('content_type_id');

            $table->string('title');
            $table->string('slug');
            $table->string('template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('content_type_id')->references('id')->on('content_types');
        });

        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('content_type_field_id');
            $table->unsignedBigInteger('localization_id');

            $table->text('value')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Add foreign key
            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('content_type_field_id')->references('id')->on('content_type_fields');
            $table->foreign('localization_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
