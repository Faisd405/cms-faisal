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
        Schema::create('content_type_fields', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('content_type_id');
            $table->string('name');
            $table->string('label');
            $table->string('type');
            $table->text('options')->nullable();
            $table->text('validation')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_localizable')->default(false);
            $table->timestamps();

            // Add foreign key
            $table->foreign('content_type_id')->references('id')->on('content_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_type_fields');
    }
};
