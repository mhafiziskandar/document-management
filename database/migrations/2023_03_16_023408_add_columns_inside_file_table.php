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
            $table->unsignedBigInteger('folder_type_id')->nullable()->after('folder_id');
            $table->string('privacy')->nullable()->after('status');
            $table->string('classification')->nullable()->after('status');
            $table->integer('can_download')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('folder_type_id');
            $table->dropColumn('privacy');
            $table->dropColumn('classification');
            $table->dropColumn('can_download');
        });
    }
};
