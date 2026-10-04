<?php

namespace App\Http\Controllers;

use App\Models\PaperSection;
use App\Models\ResearchProject;
use App\Models\ResearchTask;
use App\Services\AiResearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResearchProjectController extends Controller
{
    public function __construct(protected AiResearchService $aiService) {}

    public function index(Request $request)
    {
        $query = ResearchProject::with(['questions', 'datasets', 'claims', 'outputs', 'tasks'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('field')) {
            $query->where('field', 'like', '%'.$request->field.'%');
        }

        $projects = $query->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'field' => 'required|string|max:100',
            'summary' => 'nullable|string',
            'problem_statement' => 'nullable|string',
            'research_gap' => 'nullable|string',
            'novelty' => 'nullable|string',
            'contribution' => 'nullable|string',
            'target_deadline' => 'nullable|date',
            'target_outputs' => 'nullable|array',
        ]);

        $project = ResearchProject::create([
            'user_id' => Auth::id() ?? 1,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(4),
            'field' => $validated['field'],
            'status' => 'drafting',
            'summary' => $validated['summary'] ?? null,
            'problem_statement' => $validated['problem_statement'] ?? null,
            'research_gap' => $validated['research_gap'] ?? null,
            'novelty' => $validated['novelty'] ?? null,
            'contribution' => $validated['contribution'] ?? null,
            'target_deadline' => $validated['target_deadline'] ?? null,
            'target_outputs' => $validated['target_outputs'] ?? ['Journal Manuscript', 'Conference Paper'],
            'readiness_score' => 20,
        ]);

        // Default sections
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

        // Default starter tasks
        $tasks = [
            ['phase' => 'foundation', 'name' => 'Formulasikan Research Problem dan Novelty penelitian'],
            ['phase' => 'foundation', 'name' => 'Tentukan Research Questions terukur'],
            ['phase' => 'methodology', 'name' => 'Rancang arsitektur pengujian dan metrik evaluasi'],
            ['phase' => 'data', 'name' => 'Kumpulkan dataset observasi/eksperimen awal'],
            ['phase' => 'analysis', 'name' => 'Lakukan analisis dan petakan Finding ke Claim berdasar bukti'],
            ['phase' => 'writing', 'name' => 'Tulis draft paper ilmiah menggunakan Paper Builder'],
        ];

        foreach ($tasks as $i => $t) {
            ResearchTask::create([
                'research_project_id' => $project->id,
                'phase' => $t['phase'],
                'task_name' => $t['name'],
                'is_completed' => false,
                'priority' => 'high',
                'order' => $i + 1,
            ]);
        }

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Research Project baru berhasil dibuat.');
    }

    public function show(Request $request, ResearchProject $project)
    {
        $project->load([
            'questions',
            'literatures',
            'datasets.evidences',
            'evidences.dataset',
            'evidences.claims',
            'findings.claims',
            'claims.evidences',
            'claims.finding',
            'paperSections',
            'outputs',
            'tasks',
            'documents',
        ]);

        $activeTab = $request->query('tab', 'overview');
        $readiness = $project->readiness_breakdown;

        // Calculate unbacked claims for anti-hallucination banner
        $unbackedClaimsCount = $project->claims->where('anti_hallucination_status', 'missing_evidence')->count();
        $groundedClaimsCount = $project->claims->where('anti_hallucination_status', 'grounded')->count();

        return view('projects.show', compact(
            'project',
            'activeTab',
            'readiness',
            'unbackedClaimsCount',
            'groundedClaimsCount'
        ));
    }

    public function update(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'field' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|string',
            'summary' => 'nullable|string',
            'problem_statement' => 'nullable|string',
            'research_gap' => 'nullable|string',
            'novelty' => 'nullable|string',
            'contribution' => 'nullable|string',
            'methodology_design' => 'nullable|string',
            'sample_population' => 'nullable|string',
            'variables' => 'nullable|string',
            'instruments' => 'nullable|string',
            'procedure' => 'nullable|string',
            'evaluation_metrics' => 'nullable|string',
            'target_deadline' => 'nullable|date',
            'target_outputs' => 'nullable|array',
        ]);

        $project->update($validated);

        // Recalculate readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        $tab = $request->input('_tab', 'overview');

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => $tab])
            ->with('success', 'Informasi Research Project berhasil diperbarui.');
    }

    public function toggleTask(ResearchProject $project, ResearchTask $task)
    {
        $task->update(['is_completed' => ! $task->is_completed]);

        // Recalculate readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return back()->with('success', 'Status checklist berhasil diperbarui.');
    }

    public function storeTask(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'phase' => 'required|string',
            'priority' => 'nullable|string|in:high,medium,low',
        ]);

        $project->tasks()->create([
            'task_name' => $validated['task_name'],
            'phase' => $validated['phase'],
            'priority' => $validated['priority'] ?? 'medium',
            'is_completed' => false,
            'order' => $project->tasks()->count() + 1,
        ]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'overview'])
            ->with('success', 'Tugas baru berhasil ditambahkan ke checklist penelitian.');
    }

    public function destroy(ResearchProject $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Research Project berhasil dihapus.');
    }
}
