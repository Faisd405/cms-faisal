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
        Schema::create('content_post_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_post_id');
            $table->unsignedBigInteger('content_type_field_id');
            $table->unsignedBigInteger('localization_id')->nullable();

            $table->string('title');
            $table->text('value')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Add foreign key
            $table->foreign('content_post_id')->references('id')->on('content_posts');
            $table->foreign('content_type_field_id')->references('id')->on('content_type_fields');
            $table->foreign('localization_id')->references('id')->on('localizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_post_contents');
    }
};
