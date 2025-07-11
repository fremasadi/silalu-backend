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
        Schema::table('traffic_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('id');

            // Tambahkan foreign key constraint
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // Atur ke null jika user dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traffic_reports', function (Blueprint $table) {
            //
        });
    }
};
