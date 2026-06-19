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
        Schema::create('report_updates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['pending', 'process', 'done', 'rejected']);
            $table->text('note')->nullable();

            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_updates');
    }
};
