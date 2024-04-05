<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('records', 'id');
            $table->foreignId('contact_id')->constrained('contacts', 'id');
            $table->foreignId('budget_id')->nullable()->constrained('budgets', 'id');
            $table->string('desc');
            $table->integer('amount');
            $table->string('COA');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
