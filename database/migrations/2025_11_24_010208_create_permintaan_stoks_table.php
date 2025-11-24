<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permintaan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('bahan_id')->constrained('bahan');
            $table->decimal('jumlah', 10, 3);
            $table->enum('status', ['diajukan', 'diproses', 'dikirim', 'diterima'])->default('diajukan');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('permintaan_stok'); }
};