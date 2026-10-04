<?php

namespace App\Http\Controllers;

use App\Models\PaperSection;
use App\Models\ResearchProject;
use App\Services\AiResearchService;
use Illuminate\Http\Request;

class PaperBuilderController extends Controller
{
    public function __construct(protected AiResearchService $aiService) {}

    public function updateSection(Request $request, ResearchProject $project, PaperSection $section)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'title' => 'sometimes|required|string|max:255',
        ]);

        $content = $validated['content'] ?? '';
        $wordCount = str_word_count(strip_tags($content));

        $section->update([
            'title' => $validated['title'] ?? $section->title,
            'content' => $content,
            'word_count' => $wordCount,
            'is_drafted' => ! empty(trim($content)),
        ]);

        // Update project readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'paper', 'active_section' => $section->id])
            ->with('success', "Section '{$section->title}' berhasil disimpan.");
    }

    /**
     * AI Section-Aware Drafting Grounded in Evidence
     */
    public function generateDraft(Request $request, ResearchProject $project, PaperSection $section)
    {
        $draftResult = $this->aiService->synthesizeSectionDraft($project, $section->section_type);

        $section->update([
            'content' => $draftResult['content'],
            'word_count' => $draftResult['word_count'],
            'missing_evidence_flags' => $draftResult['missing_evidence_flags'],
            'is_drafted' => true,
        ]);

        // Update project readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        $notice = ! empty($draftResult['missing_evidence_flags'])
            ? 'Draf disusun berbasis data empiris! '.count($draftResult['missing_evidence_flags']).' klaim tanpa bukti telah ditandai/dikecualikan oleh Anti-Hallucination Guard.'
            : 'Draf berhasil disusun dari data evidence dan temuan yang terverifikasi!';

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'paper', 'active_section' => $section->id])
            ->with('success', $notice);
    }

    /**
     * Export Full Paper to Markdown
     */
    public function exportMarkdown(ResearchProject $project)
    {
        $project->load(['paperSections', 'literatures', 'claims.evidences']);

        $md = "# {$project->title}\n\n";
        $md .= '**Author:** '.($project->user->name ?? 'Researcher')."\n";
        $md .= "**Field:** {$project->field}\n";
        $md .= '**Generated via Research OS** | '.now()->format('d F Y')."\n\n";
        $md .= "---\n\n";

        foreach ($project->paperSections as $section) {
            $md .= "## {$section->title}\n\n";
            $md .= ($section->content ?? '_[Section belum didraf]_')."\n\n";
        }

        $filename = 'Paper_'.preg_replace('/[^A-Za-z0-9_\-]/', '_', $project->title).'.md';

        return response($md, 200, [
            'Content-Type' => 'text/markdown; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Academic Print & PDF View
     */
    public function exportPrint(ResearchProject $project)
    {
        $project->load(['paperSections', 'literatures', 'claims.evidences', 'evidences', 'outputs']);

        return view('projects.paper_print', compact('project'));
    }
}
