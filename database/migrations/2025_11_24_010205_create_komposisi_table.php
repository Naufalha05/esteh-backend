<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('komposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')
                  ->constrained('produk')
                  ->onDelete('cascade');
            $table->foreignId('bahan_id')
                  ->constrained('bahan')
                  ->onDelete('cascade');
            $table->decimal('quantity', 10, 3);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('komposisi'); }
};