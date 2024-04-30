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
            $table->foreignId('record_id')->constrained('records', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('contact_id')->constrained('contacts', 'id');
            $table->foreignId('budget_id')->nullable()->constrained('budgets', 'id');
            $table->foreignId('coa_id')->constrained('account_sub_categories', 'id');
            $table->string('desc');
            $table->decimal('amount', 60, 4);
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
