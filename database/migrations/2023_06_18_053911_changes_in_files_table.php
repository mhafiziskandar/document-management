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
        Schema::table('files', function (Blueprint $table) {
            $table->string('filename')->nullable()->change();
            $table->string('path')->nullable()->change();
            $table->string('extension')->nullable()->change();
            $table->string('size')->nullable()->change();
            $table->longText('url')->nullable()->after('folder_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('filename')->nullable(false)->change();
            $table->string('path')->nullable(false)->change();
            $table->string('extension')->nullable(false)->change();
            $table->string('size')->nullable(false)->change();
            $table->dropColumn('url');
        });
    }
};
