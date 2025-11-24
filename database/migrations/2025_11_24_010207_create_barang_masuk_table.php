<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan');
            $table->decimal('jumlah', 10, 3);
            $table->dateTime('tanggal');
            $table->string('supplier');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barang_masuk'); }
};