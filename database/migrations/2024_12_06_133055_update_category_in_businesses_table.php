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
        Schema::table('businesses', function (Blueprint $table) {
            // Drop the old category column if it exists
            $table->dropColumn('category');

            // Add the new category_id column
            $table->unsignedBigInteger('category_id');

            // Add the foreign key constraint for category_id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
             // Revert the change if necessary
             $table->dropForeign(['category_id']);
             $table->dropColumn('category_id');
             $table->string('category'); // Adding the category field back as a string (optional)
         
        });
    }
};
