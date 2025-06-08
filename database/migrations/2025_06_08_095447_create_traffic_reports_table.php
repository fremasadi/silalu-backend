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
        Schema::create('traffic_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('traffic_id');  // Relasi ke traffic
            $table->text('masalah');                   // Deskripsi masalah
            $table->string('foto')->nullable();        // Path foto bukti
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending'); // Status laporan
            $table->timestamps();

            // Foreign key
            $table->foreign('traffic_id')->references('id')->on('traffic')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_reports');
    }
};
