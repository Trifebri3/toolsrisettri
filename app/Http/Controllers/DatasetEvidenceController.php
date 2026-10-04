<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\Evidence;
use App\Models\ResearchProject;
use Illuminate\Http\Request;

class DatasetEvidenceController extends Controller
{
    public function storeDataset(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'source_type' => 'required|string',
            'description' => 'nullable|string',
            'collection_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'record_count' => 'nullable|integer|min:0',
            'status' => 'nullable|string',
        ]);

        $project->datasets()->create($validated);

        // Update readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Dataset baru berhasil ditambahkan.');
    }

    public function storeEvidence(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'dataset_id' => 'nullable|exists:datasets,id',
            'title' => 'required|string|max:255',
            'evidence_type' => 'required|string',
            'description' => 'required|string',
            'collected_at' => 'nullable|date',
            'quality_status' => 'required|string',
            'metric_key' => 'nullable|string',
            'metric_value' => 'nullable|string',
        ]);

        $dataPayload = null;
        if (! empty($validated['metric_key']) && ! empty($validated['metric_value'])) {
            $dataPayload = [
                $validated['metric_key'] => $validated['metric_value'],
            ];
        }

        $project->evidences()->create([
            'dataset_id' => $validated['dataset_id'] ?? null,
            'title' => $validated['title'],
            'evidence_type' => $validated['evidence_type'],
            'description' => $validated['description'],
            'data_payload' => $dataPayload,
            'collected_at' => $validated['collected_at'] ?? now(),
            'quality_status' => $validated['quality_status'],
        ]);

        // Update readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Bukti empiris (Evidence) berhasil disimpan ke repositori project.');
    }

    public function destroyDataset(ResearchProject $project, Dataset $dataset)
    {
        $dataset->delete();
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Dataset berhasil dihapus.');
    }

    public function destroyEvidence(ResearchProject $project, Evidence $evidence)
    {
        $evidence->delete();
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Evidence berhasil dihapus.');
    }
}
