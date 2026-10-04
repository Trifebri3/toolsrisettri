<?php

use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\DatasetEvidenceController;
use App\Http\Controllers\FindingClaimController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\PaperBuilderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\ResearchDashboardController;
use App\Http\Controllers\ResearchIdeaController;
use App\Http\Controllers\ResearchOutputController;
use App\Http\Controllers\ResearchProjectController;
use App\Http\Controllers\ResearchQuestionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

// PWA Offline Fallback Page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [ResearchDashboardController::class, 'index'])->name('dashboard');

    // 2. Idea Repository
    Route::get('/ideas', [ResearchIdeaController::class, 'index'])->name('ideas.index');
    Route::post('/ideas', [ResearchIdeaController::class, 'store'])->name('ideas.store');
    Route::post('/ideas/classify', [ResearchIdeaController::class, 'classify'])->name('ideas.classify');
    Route::post('/ideas/{idea}/promote', [ResearchIdeaController::class, 'promote'])->name('ideas.promote');
    Route::delete('/ideas/{idea}', [ResearchIdeaController::class, 'destroy'])->name('ideas.destroy');

    // 3. Research Workspace (Projects)
    Route::get('/projects', [ResearchProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ResearchProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ResearchProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ResearchProjectController::class, 'show'])->name('projects.show');
    Route::put('/projects/{project}', [ResearchProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ResearchProjectController::class, 'destroy'])->name('projects.destroy');

    // Tasks & Dynamic Checklist
    Route::post('/projects/{project}/tasks', [ResearchProjectController::class, 'storeTask'])->name('projects.tasks.store');
    Route::post('/projects/{project}/tasks/{task}/toggle', [ResearchProjectController::class, 'toggleTask'])->name('projects.tasks.toggle');

    // Research Questions
    Route::post('/projects/{project}/questions', [ResearchQuestionController::class, 'store'])->name('projects.questions.store');
    Route::put('/projects/{project}/questions/{question}', [ResearchQuestionController::class, 'update'])->name('projects.questions.update');
    Route::delete('/projects/{project}/questions/{question}', [ResearchQuestionController::class, 'destroy'])->name('projects.questions.destroy');

    // 4. Literature Repository
    Route::get('/literature', [LiteratureController::class, 'index'])->name('literature.index');
    Route::post('/literature', [LiteratureController::class, 'store'])->name('literature.store');
    Route::delete('/literature/{literature}', [LiteratureController::class, 'destroy'])->name('literature.destroy');

    // 5. Evidence & Data Repository
    Route::post('/projects/{project}/datasets', [DatasetEvidenceController::class, 'storeDataset'])->name('projects.datasets.store');
    Route::delete('/projects/{project}/datasets/{dataset}', [DatasetEvidenceController::class, 'destroyDataset'])->name('projects.datasets.destroy');
    Route::post('/projects/{project}/evidences', [DatasetEvidenceController::class, 'storeEvidence'])->name('projects.evidences.store');
    Route::delete('/projects/{project}/evidences/{evidence}', [DatasetEvidenceController::class, 'destroyEvidence'])->name('projects.evidences.destroy');

    // Project Documents (File Uploads)
    Route::post('/projects/{project}/documents', [ProjectDocumentController::class, 'store'])->name('projects.documents.store');
    Route::get('/projects/{project}/documents/{document}/download', [ProjectDocumentController::class, 'download'])->name('projects.documents.download');
    Route::get('/projects/{project}/documents/{document}/view', [ProjectDocumentController::class, 'view'])->name('projects.documents.view');
    Route::delete('/projects/{project}/documents/{document}', [ProjectDocumentController::class, 'destroy'])->name('projects.documents.destroy');

    // 6. Findings, Claims & Anti-Hallucination Evidence Links
    Route::post('/projects/{project}/findings', [FindingClaimController::class, 'storeFinding'])->name('projects.findings.store');
    Route::delete('/projects/{project}/findings/{finding}', [FindingClaimController::class, 'destroyFinding'])->name('projects.findings.destroy');
    Route::post('/projects/{project}/claims', [FindingClaimController::class, 'storeClaim'])->name('projects.claims.store');
    Route::delete('/projects/{project}/claims/{claim}', [FindingClaimController::class, 'destroyClaim'])->name('projects.claims.destroy');
    Route::post('/projects/{project}/claims/{claim}/link-evidence', [FindingClaimController::class, 'linkEvidence'])->name('projects.claims.link-evidence');
    Route::delete('/projects/{project}/claims/{claim}/evidences/{evidence}', [FindingClaimController::class, 'unlinkEvidence'])->name('projects.claims.unlink-evidence');

    // 7. Paper Builder & Export
    Route::put('/projects/{project}/paper-sections/{section}', [PaperBuilderController::class, 'updateSection'])->name('projects.paper-sections.update');
    Route::post('/projects/{project}/paper-sections/{section}/draft', [PaperBuilderController::class, 'generateDraft'])->name('projects.paper-sections.draft');
    Route::get('/projects/{project}/paper/export-markdown', [PaperBuilderController::class, 'exportMarkdown'])->name('projects.paper.export-markdown');
    Route::get('/projects/{project}/paper/print', [PaperBuilderController::class, 'exportPrint'])->name('projects.paper.print');

    // 8. Output Manager & Publication Tracker
    Route::get('/outputs', [ResearchOutputController::class, 'index'])->name('outputs.index');
    Route::delete('/outputs/{output}', [ResearchOutputController::class, 'destroyDirect'])->name('outputs.destroy');
    Route::post('/projects/{project}/outputs', [ResearchOutputController::class, 'store'])->name('projects.outputs.store');
    Route::put('/projects/{project}/outputs/{output}', [ResearchOutputController::class, 'update'])->name('projects.outputs.update');
    Route::delete('/projects/{project}/outputs/{output}', [ResearchOutputController::class, 'destroy'])->name('projects.outputs.destroy');

    // 9. AI Research Assistant
    Route::post('/projects/{project}/ai-query', [AiAssistantController::class, 'query'])->name('projects.ai.query');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
