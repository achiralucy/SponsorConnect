<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')
                  ->constrained('sponsor_profiles')
                  ->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('benefit');
            $table->text('persyaratan');
            $table->date('batas_pengajuan');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};