<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsorship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsorship_id')
                  ->constrained('sponsorships')
                  ->cascadeOnDelete();
            $table->foreignId('panitia_id')
                  ->constrained('panitia_profiles')
                  ->cascadeOnDelete();
            $table->string('nama_event');
            $table->text('deskripsi_event');
            $table->date('tanggal_event');
            $table->string('lokasi_event');
            $table->string('proposal');
            $table->text('pesan')->nullable();
            $table->string('status')->default('pending');
            $table->text('catatan_sponsor')->nullable();
            $table->timestamps();

            $table->unique(['sponsorship_id', 'panitia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorship_applications');
    }
};