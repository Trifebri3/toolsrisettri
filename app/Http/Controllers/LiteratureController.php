<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiteratureController extends Controller
{
    public function index(Request $request)
    {
        $projects = ResearchProject::all();
        $query = Literature::with('project')->latest();

        if ($request->filled('project_id')) {
            $query->where('research_project_id', $request->project_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('authors', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('key_findings', 'like', "%{$search}%");
            });
        }

        $literatures = $query->paginate(12);

        // Prepare literature matrix
        $matrixItems = (clone $query)->take(20)->get();

        return view('literature.index', compact('literatures', 'projects', 'matrixItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'research_project_id' => 'nullable|exists:research_projects,id',
            'title' => 'required|string|max:255',
            'authors' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:2030',
            'venue' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'abstract' => 'nullable|string',
            'key_findings' => 'nullable|string',
            'methodology_used' => 'nullable|string',
            'limitations' => 'nullable|string',
            'our_differentiation' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id() ?? 1;

        // Auto generate citation key if missing
        if (! empty($validated['authors']) && ! empty($validated['year'])) {
            $firstAuthor = explode(',', $validated['authors'])[0];
            $firstAuthor = explode(' ', trim($firstAuthor))[0];
            $validated['citation_key'] = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstAuthor)).$validated['year'];
        }

        Literature::create($validated);

        if ($request->filled('research_project_id')) {
            return redirect()->route('projects.show', ['project' => $validated['research_project_id'], 'tab' => 'foundation'])
                ->with('success', 'Referensi literatur berhasil ditambahkan ke project.');
        }

        return redirect()->route('literature.index')->with('success', 'Literatur baru berhasil dicatat dalam repositori.');
    }

    public function destroy(Literature $literature)
    {
        $projectId = $literature->research_project_id;
        $literature->delete();

        if (request()->has('_from_project') && $projectId) {
            return redirect()->route('projects.show', ['project' => $projectId, 'tab' => 'foundation'])
                ->with('success', 'Literatur berhasil dihapus dari project.');
        }

        return redirect()->route('literature.index')->with('success', 'Literatur berhasil dihapus.');
    }
}
