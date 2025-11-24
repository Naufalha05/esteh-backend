<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_outlet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->cascadeOnDelete();
            $table->foreignId('bahan_id')->constrained('bahan')->cascadeOnDelete();
            $table->decimal('stok', 10, 3)->default(0);
            $table->timestamps();
            $table->unique(['outlet_id', 'bahan_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('stok_outlet'); }
};