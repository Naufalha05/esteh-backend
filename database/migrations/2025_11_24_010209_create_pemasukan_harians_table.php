<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemasukan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->date('tanggal');
            $table->decimal('total_pemasukan', 14, 2)->default(0);
            $table->timestamps();
            $table->unique(['outlet_id', 'tanggal']);
        });
    }
    public function down(): void { Schema::dropIfExists('pemasukan_harian'); }
};