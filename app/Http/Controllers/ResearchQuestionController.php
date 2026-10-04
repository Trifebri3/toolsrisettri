<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\ResearchQuestion;
use Illuminate\Http\Request;

class ResearchQuestionController extends Controller
{
    public function store(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'objective' => 'nullable|string',
            'status' => 'nullable|string|in:open,investigating,answered',
        ]);

        $project->questions()->create([
            'question' => $validated['question'],
            'objective' => $validated['objective'] ?? null,
            'status' => $validated['status'] ?? 'open',
            'order' => $project->questions()->count() + 1,
        ]);

        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'foundation'])
            ->with('success', 'Research Question berhasil ditambahkan.');
    }

    public function update(Request $request, ResearchProject $project, ResearchQuestion $question)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'objective' => 'nullable|string',
            'status' => 'required|string|in:open,investigating,answered',
        ]);

        $question->update($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'foundation'])
            ->with('success', 'Research Question berhasil diperbarui.');
    }

    public function destroy(ResearchProject $project, ResearchQuestion $question)
    {
        $question->delete();
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'foundation'])
            ->with('success', 'Research Question berhasil dihapus.');
    }
}
