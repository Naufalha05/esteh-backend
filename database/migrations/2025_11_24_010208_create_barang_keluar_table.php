<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->decimal('jumlah', 10, 3);
            $table->dateTime('tanggal_keluar');
            $table->enum('status', ['dikirim', 'diterima'])->default('dikirim');
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barang_keluar'); }
};