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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id(); // Primary key for feedback
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('business_id'); // Foreign key to businesses table
            $table->text('comment'); // User's feedback comment
            $table->integer('stars')->default(0); // Rating from 1 to 5
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
