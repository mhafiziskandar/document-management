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
        Schema::create('folderables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folderable_id');
            $table->string('folderable_type')->after('folderable_id');
            $table->unsignedBigInteger('folder_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folderables');
    }
};
