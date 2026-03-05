<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 100);
            $table->text('description');
            $table->integer('price');
            $table->string('brand', 100)->nullable();
            $table->string('size', 20)->nullable();
            $table->string('color', 30)->nullable();
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor']);
            $table->enum('status', ['draft', 'on_sale', 'sold_out', 'suspended'])->default('draft');
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
