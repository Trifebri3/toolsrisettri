<?php

namespace App\Http\Controllers;

use App\Models\PaperSection;
use App\Models\ResearchIdea;
use App\Models\ResearchProject;
use App\Models\ResearchQuestion;
use App\Models\ResearchTask;
use App\Services\AiResearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResearchIdeaController extends Controller
{
    public function __construct(protected AiResearchService $aiService) {}

    public function index(Request $request)
    {
        $query = ResearchIdea::with('project')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('field')) {
            $query->where('field', 'like', '%'.$request->field.'%');
        }

        $ideas = $query->paginate(12);

        return view('ideas.index', compact('ideas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'field' => 'nullable|string',
            'priority' => 'nullable|string|in:high,medium,low',
        ]);

        // Run AI classification
        $classification = $this->aiService->classifyIdea(
            $validated['title'],
            $validated['description'] ?? null
        );

        $idea = ResearchIdea::create([
            'user_id' => Auth::id() ?? 1,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'field' => ! empty($validated['field']) ? $validated['field'] : $classification['field'],
            'research_type' => $classification['research_type'],
            'priority' => $validated['priority'] ?? $classification['priority'],
            'potential_outputs' => $classification['potential_outputs'],
            'status' => 'captured',
            'ai_analysis' => $classification['analysis'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ide penelitian berhasil ditangkap dalam hitungan detik!',
                'idea' => $idea,
            ]);
        }

        return redirect()->route('ideas.index')->with('success', 'Ide baru berhasil ditangkap dan diklasifikasikan oleh AI!');
    }

    public function classify(Request $request)
    {
        $request->validate(['title' => 'required|string']);
        $result = $this->aiService->classifyIdea($request->title, $request->description);

        return response()->json($result);
    }

    /**
     * 1-Click Promote Idea to Research Project
     */
    public function promote(ResearchIdea $idea)
    {
        if ($idea->research_project_id) {
            return redirect()->route('projects.show', $idea->research_project_id)
                ->with('info', 'Ide ini telah dipromosikan ke Research Project.');
        }

        $analysis = $idea->ai_analysis ?? [];

        // Create new Research Project from Idea
        $project = ResearchProject::create([
            'user_id' => Auth::id() ?? $idea->user_id,
            'research_idea_id' => $idea->id,
            'title' => $idea->title,
            'slug' => Str::slug($idea->title).'-'.Str::random(4),
            'field' => $idea->field,
            'status' => 'drafting',
            'summary' => $idea->description,
            'target_deadline' => now()->addMonths(3),
            'target_outputs' => $idea->potential_outputs ?? ['Journal Manuscript', 'Conference Paper'],
            'problem_statement' => $idea->description ?? 'Identifikasi permasalahan penelitian belum dirinci secara kuantitatif.',
            'research_gap' => $analysis['suggested_gap'] ?? 'Kesenjangan literatur belum sepenuhnya dipetakan.',
            'novelty' => $analysis['novelty_angle'] ?? 'Pendekatan baru yang diusulkan untuk mengatasi batasan metode saat ini.',
            'contribution' => 'Kontribusi teoritis dan bukti empiris lapangan yang teruji.',
            'methodology_design' => $analysis['recommended_methods'][0] ?? 'Experimental Design',
            'readiness_score' => 25,
        ]);

        // Link project back to idea
        $idea->update([
            'status' => 'promoted',
            'research_project_id' => $project->id,
        ]);

        // Seed default research questions from AI analysis
        if (! empty($analysis['recommended_questions'])) {
            foreach ($analysis['recommended_questions'] as $idx => $qText) {
                ResearchQuestion::create([
                    'research_project_id' => $project->id,
                    'question' => $qText,
                    'objective' => 'Menjawab pertanyaan penelitian secara empiris.',
                    'status' => 'open',
                    'order' => $idx + 1,
                ]);
            }
        }

        // Initialize standard academic paper sections
        $sections = [
            ['type' => 'abstract', 'title' => 'Abstract & Keywords', 'order' => 1],
            ['type' => 'introduction', 'title' => '1. Introduction', 'order' => 2],
            ['type' => 'literature_review', 'title' => '2. Literature Review & Related Work', 'order' => 3],
            ['type' => 'methodology', 'title' => '3. Methodology & System Architecture', 'order' => 4],
            ['type' => 'results', 'title' => '4. Results & Evidence Evaluation', 'order' => 5],
            ['type' => 'discussion', 'title' => '5. Discussion & Implications', 'order' => 6],
            ['type' => 'conclusion', 'title' => '6. Conclusion & Future Work', 'order' => 7],
            ['type' => 'references', 'title' => 'References', 'order' => 8],
        ];

        foreach ($sections as $s) {
            PaperSection::create([
                'research_project_id' => $project->id,
                'section_type' => $s['type'],
                'title' => $s['title'],
                'order' => $s['order'],
                'content' => null,
                'is_drafted' => false,
            ]);
        }

        // Initialize standard research tasks
        $starterTasks = [
            ['phase' => 'foundation', 'name' => 'Pertajam Problem Statement dan Research Gap spesifik'],
            ['phase' => 'foundation', 'name' => 'Validasi Research Questions dengan pembimbing / co-author'],
            ['phase' => 'methodology', 'name' => 'Tentukan variabel, sampel eksperimen, dan metrik evaluasi'],
            ['phase' => 'data', 'name' => 'Kumpulkan dataset awal atau bukti (evidence) observasi'],
            ['phase' => 'analysis', 'name' => 'Ubah temuan analisis menjadi Scientific Claims berdasar bukti'],
            ['phase' => 'writing', 'name' => 'Gunakan Paper Builder untuk menyusun draf dari data yang ada'],
        ];

        foreach ($starterTasks as $i => $st) {
            ResearchTask::create([
                'research_project_id' => $project->id,
                'phase' => $st['phase'],
                'task_name' => $st['name'],
                'is_completed' => false,
                'priority' => 'high',
                'order' => $i + 1,
            ]);
        }

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Ide berhasil dipromosikan menjadi Research Project aktif! Struktur penelitian dan checklist awal telah disiapkan.');
    }

    public function destroy(ResearchIdea $idea)
    {
        $idea->delete();

        return redirect()->route('ideas.index')->with('success', 'Ide berhasil dihapus.');
    }
}
