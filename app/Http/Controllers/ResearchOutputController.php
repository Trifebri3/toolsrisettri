<?php

namespace App\Http\Controllers;

use App\Models\ResearchOutput;
use App\Models\ResearchProject;
use Illuminate\Http\Request;

class ResearchOutputController extends Controller
{
    public function index(Request $request)
    {
        $projects = ResearchProject::all();
        $query = ResearchOutput::with('project')->latest();

        if ($request->filled('project_id')) {
            $query->where('research_project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('output_type')) {
            $query->where('output_type', $request->output_type);
        }
        if ($request->filled('topic')) {
            if ($request->topic === 'saintek') {
                $query->whereIn('output_type', ['journal_manuscript', 'conference_paper', 'dataset_repository', 'technical_report']);
            } elseif ($request->topic === 'pkm') {
                $query->whereIn('output_type', ['community_service', 'policy_brief']);
            }
        }

        $outputs = $query->paginate(12);

        // Stats
        $total = ResearchOutput::count();
        $published = ResearchOutput::where('status', 'published')->count();
        $underReview = ResearchOutput::whereIn('status', ['submitted', 'under_review', 'revision'])->count();
        $accepted = ResearchOutput::where('status', 'accepted')->count();
        $totalSaintek = ResearchOutput::whereIn('output_type', ['journal_manuscript', 'conference_paper', 'dataset_repository', 'technical_report'])->count();
        $totalPkm = ResearchOutput::whereIn('output_type', ['community_service', 'policy_brief'])->count();

        return view('outputs.index', compact('outputs', 'projects', 'total', 'published', 'underReview', 'accepted', 'totalSaintek', 'totalPkm'));
    }

    public function store(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'output_type' => 'required|string',
            'target_venue' => 'nullable|string|max:255',
            'indexing' => 'nullable|string|max:100',
            'status' => 'required|string',
            'submission_date' => 'nullable|date',
            'doi_or_url' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $project->outputs()->create($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'outputs'])
            ->with('success', 'Luaran penelitian (Research Output) baru berhasil ditambahkan.');
    }

    public function update(Request $request, ResearchProject $project, ResearchOutput $output)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'output_type' => 'required|string',
            'target_venue' => 'nullable|string|max:255',
            'indexing' => 'nullable|string|max:100',
            'status' => 'required|string',
            'submission_date' => 'nullable|date',
            'doi_or_url' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $output->update($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'outputs'])
            ->with('success', 'Status publikasi luaran berhasil diperbarui.');
    }

    public function destroy(ResearchProject $project, ResearchOutput $output)
    {
        $output->delete();

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'outputs'])
            ->with('success', 'Luaran penelitian berhasil dihapus.');
    }

    public function destroyDirect(ResearchOutput $output)
    {
        $output->delete();

        return back()->with('success', 'Target luaran penelitian berhasil dihapus.');
    }
}
