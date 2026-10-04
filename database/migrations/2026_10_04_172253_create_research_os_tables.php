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
        // 1. Idea Repository
        Schema::create('research_ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('field')->default('General Science');
            $table->string('research_type')->default('experimental');
            $table->string('priority')->default('medium'); // high, medium, low
            $table->json('potential_outputs')->nullable();
            $table->string('status')->default('captured'); // captured, promoted, archived
            $table->json('ai_analysis')->nullable();
            $table->unsignedBigInteger('research_project_id')->nullable();
            $table->timestamps();
        });

        // 2. Research Projects (Workspace Core)
        Schema::create('research_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('research_idea_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('field')->default('Computer Science & IoT');
            $table->string('status')->default('drafting'); // drafting, data_collection, analysis, paper_writing, under_review, published
            $table->text('summary')->nullable();
            $table->date('target_deadline')->nullable();
            $table->json('target_outputs')->nullable();

            // Research Foundation
            $table->text('problem_statement')->nullable();
            $table->text('research_gap')->nullable();
            $table->text('novelty')->nullable();
            $table->text('contribution')->nullable();

            // Methodology
            $table->string('methodology_design')->nullable();
            $table->text('sample_population')->nullable();
            $table->text('variables')->nullable();
            $table->text('instruments')->nullable();
            $table->text('procedure')->nullable();
            $table->text('evaluation_metrics')->nullable();

            // Readiness Score (0-100)
            $table->unsignedTinyInteger('readiness_score')->default(15);
            $table->timestamps();
        });

        // 3. Research Questions & Objectives
        Schema::create('research_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->text('question');
            $table->text('objective')->nullable();
            $table->string('status')->default('open'); // open, investigating, answered
            $table->unsignedInteger('order')->default(1);
            $table->timestamps();
        });

        // 4. Literature Repository
        Schema::create('literatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('research_project_id')->nullable()->constrained('research_projects')->nullOnDelete();
            $table->string('title');
            $table->string('authors')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('venue')->nullable();
            $table->string('doi')->nullable();
            $table->string('url')->nullable();
            $table->text('abstract')->nullable();
            $table->text('key_findings')->nullable();
            $table->text('methodology_used')->nullable();
            $table->text('limitations')->nullable();
            $table->text('our_differentiation')->nullable();
            $table->string('citation_key')->nullable();
            $table->timestamps();
        });

        // 5. Datasets
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->string('name');
            $table->string('source_type')->default('sensor'); // sensor, survey, experiment, interview, benchmark
            $table->text('description')->nullable();
            $table->date('collection_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('record_count')->default(0);
            $table->string('status')->default('verified'); // raw, cleaned, verified
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 6. Evidences (Data Points, Sensor Readings, Photos, Logs)
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->foreignId('dataset_id')->nullable()->constrained('datasets')->nullOnDelete();
            $table->string('title');
            $table->string('evidence_type')->default('data_point'); // data_point, sensor_reading, experiment_metric, photo, quote, document, test_log
            $table->text('description')->nullable();
            $table->json('data_payload')->nullable();
            $table->string('media_path')->nullable();
            $table->date('collected_at')->nullable();
            $table->string('quality_status')->default('verified'); // unverified, verified, ground_truth
            $table->timestamps();
        });

        // 7. Findings (Derived from Evidence)
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->string('title');
            $table->text('statement');
            $table->string('finding_type')->default('quantitative'); // quantitative, qualitative, comparative, anomalous
            $table->json('metrics_summary')->nullable();
            $table->unsignedTinyInteger('confidence_score')->default(90);
            $table->text('interpretation')->nullable();
            $table->timestamps();
        });

        // 8. Scientific Claims (Must be grounded in Evidence)
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->foreignId('finding_id')->nullable()->constrained('findings')->nullOnDelete();
            $table->text('claim_text');
            $table->string('section_target')->default('results'); // results, discussion, introduction, conclusion
            $table->string('anti_hallucination_status')->default('missing_evidence'); // grounded, weak_evidence, missing_evidence
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // 9. Claim <-> Evidence Links (Traceability Matrix)
        Schema::create('claim_evidence_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->cascadeOnDelete();
            $table->foreignId('evidence_id')->constrained('evidences')->cascadeOnDelete();
            $table->text('relevance_note')->nullable();
            $table->timestamps();
        });

        // 10. Paper Sections (Paper Builder)
        Schema::create('paper_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->string('section_type'); // abstract, introduction, literature_review, methodology, results, discussion, conclusion, references
            $table->string('title');
            $table->unsignedSmallInteger('order')->default(1);
            $table->longText('content')->nullable();
            $table->json('missing_evidence_flags')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->boolean('is_drafted')->default(false);
            $table->timestamps();
        });

        // 11. Multi-Output Engine & Publication Tracker
        Schema::create('research_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->string('title');
            $table->string('output_type')->default('journal_manuscript'); // journal_manuscript, conference_paper, community_service, technical_report, dataset_repository, prototype_docs, policy_brief
            $table->string('target_venue')->nullable();
            $table->string('indexing')->nullable(); // Scopus Q1, Sinta 2, IEEE, etc.
            $table->string('status')->default('drafting'); // idea, drafting, submitted, under_review, revision, accepted, published
            $table->date('submission_date')->nullable();
            $table->string('doi_or_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 12. Dynamic Research Checklist & Tasks
        Schema::create('research_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->string('phase')->default('foundation'); // foundation, data, methodology, analysis, writing, publication
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->string('priority')->default('medium');
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_tasks');
        Schema::dropIfExists('research_outputs');
        Schema::dropIfExists('paper_sections');
        Schema::dropIfExists('claim_evidence_links');
        Schema::dropIfExists('claims');
        Schema::dropIfExists('findings');
        Schema::dropIfExists('evidences');
        Schema::dropIfExists('datasets');
        Schema::dropIfExists('literatures');
        Schema::dropIfExists('research_questions');
        Schema::dropIfExists('research_projects');
        Schema::dropIfExists('research_ideas');
    }
};
