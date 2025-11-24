<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('karyawan_id')->constrained('users');
            $table->dateTime('tanggal');
            $table->decimal('total', 14, 2);
            $table->enum('metode_bayar', ['tunai', 'qris']);
            $table->string('bukti_qris')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('transaksi'); }
};