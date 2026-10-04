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
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('document_category')->default('other'); // proposal, instrument, raw_data, photo_evidence, draft_report, literature, other
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->default(0); // bytes
            $table->string('file_extension', 20)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
