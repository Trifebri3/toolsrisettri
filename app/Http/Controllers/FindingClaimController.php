<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimEvidenceLink;
use App\Models\Evidence;
use App\Models\Finding;
use App\Models\ResearchProject;
use Illuminate\Http\Request;

class FindingClaimController extends Controller
{
    public function storeFinding(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'statement' => 'required|string',
            'finding_type' => 'required|string',
            'confidence_score' => 'nullable|integer|min:0|max:100',
            'interpretation' => 'nullable|string',
            'metric_label' => 'nullable|string',
            'metric_val' => 'nullable|string',
        ]);

        $metrics = null;
        if (! empty($validated['metric_label']) && ! empty($validated['metric_val'])) {
            $metrics = [$validated['metric_label'] => $validated['metric_val']];
        }

        $project->findings()->create([
            'title' => $validated['title'],
            'statement' => $validated['statement'],
            'finding_type' => $validated['finding_type'],
            'confidence_score' => $validated['confidence_score'] ?? 90,
            'interpretation' => $validated['interpretation'] ?? null,
            'metrics_summary' => $metrics,
        ]);

        // Update project readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with('success', 'Temuan analisis (Finding) berhasil dicatat.');
    }

    public function storeClaim(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'finding_id' => 'nullable|exists:findings,id',
            'claim_text' => 'required|string',
            'section_target' => 'required|string',
            'evidence_id' => 'nullable|exists:evidences,id',
            'relevance_note' => 'nullable|string',
        ]);

        $hasEvidence = ! empty($validated['evidence_id']);

        $claim = $project->claims()->create([
            'finding_id' => $validated['finding_id'] ?? null,
            'claim_text' => $validated['claim_text'],
            'section_target' => $validated['section_target'],
            'anti_hallucination_status' => $hasEvidence ? 'grounded' : 'missing_evidence',
            'is_verified' => $hasEvidence,
        ]);

        if ($hasEvidence) {
            ClaimEvidenceLink::create([
                'claim_id' => $claim->id,
                'evidence_id' => $validated['evidence_id'],
                'relevance_note' => $validated['relevance_note'] ?? 'Tautan bukti langsung saat klaim dibuat.',
            ]);
        }

        // Update project readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        $msg = $hasEvidence
            ? 'Scientific Claim berhasil dibuat dan terhubung ke Evidence (Status: Grounded).'
            : 'Scientific Claim dibuat, namun berstatus "Missing Evidence". Harap tautkan bukti sebelum draf paper difinalisasi.';

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with($hasEvidence ? 'success' : 'warning', $msg);
    }

    /**
     * Link an Evidence to a Claim (Anti-Hallucination verification)
     */
    public function linkEvidence(Request $request, ResearchProject $project, Claim $claim)
    {
        $validated = $request->validate([
            'evidence_id' => 'required|exists:evidences,id',
            'relevance_note' => 'nullable|string',
        ]);

        // Check if already linked
        $exists = ClaimEvidenceLink::where('claim_id', $claim->id)
            ->where('evidence_id', $validated['evidence_id'])
            ->exists();

        if (! $exists) {
            ClaimEvidenceLink::create([
                'claim_id' => $claim->id,
                'evidence_id' => $validated['evidence_id'],
                'relevance_note' => $validated['relevance_note'] ?? 'Bukti pendukung terverifikasi.',
            ]);
        }

        // Update status
        $claim->updateAntiHallucinationStatus();

        // Update project readiness
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with('success', 'Bukti empiris berhasil ditautkan ke klaim ilmiah! Status anti-halusinasi terverifikasi (Grounded).');
    }

    /**
     * Unlink an Evidence from a Claim
     */
    public function unlinkEvidence(ResearchProject $project, Claim $claim, Evidence $evidence)
    {
        $claim->evidences()->detach($evidence->id);

        $claim->updateAntiHallucinationStatus();

        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with('info', 'Tautan bukti ke klaim telah dilepas.');
    }

    public function destroyFinding(ResearchProject $project, Finding $finding)
    {
        $finding->delete();
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with('success', 'Finding berhasil dihapus.');
    }

    public function destroyClaim(ResearchProject $project, Claim $claim)
    {
        $claim->delete();
        $readiness = $project->readiness_breakdown;
        $project->update(['readiness_score' => $readiness['overall']]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'analysis'])
            ->with('success', 'Klaim berhasil dihapus.');
    }
}
