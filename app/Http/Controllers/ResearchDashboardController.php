<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Dataset;
use App\Models\Evidence;
use App\Models\ResearchIdea;
use App\Models\ResearchOutput;
use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResearchDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get active projects
        $projects = ResearchProject::with(['questions', 'datasets', 'claims', 'outputs', 'tasks'])
            ->latest()
            ->get();

        // Primary featured active project (first one)
        $primaryProject = $projects->first();

        // Recent ideas
        $recentIdeas = ResearchIdea::latest()->take(5)->get();

        // Recent evidence stream
        $recentEvidences = Evidence::with(['project', 'dataset'])
            ->latest()
            ->take(6)
            ->get();

        // Stats summary
        $totalIdeas = ResearchIdea::count();
        $totalProjects = ResearchProject::count();
        $totalDatasets = Dataset::count();
        $totalEvidences = Evidence::count();
        $groundedClaims = Claim::where('anti_hallucination_status', 'grounded')->count();
        $unbackedClaims = Claim::where('anti_hallucination_status', 'missing_evidence')->count();
        $totalOutputs = ResearchOutput::count();
        $publishedOutputs = ResearchOutput::where('status', 'published')->count();

        // Next Action suggestions engine
        $nextActions = [];
        if ($primaryProject) {
            $uncompletedTask = $primaryProject->tasks()->where('is_completed', false)->first();
            if ($uncompletedTask) {
                $nextActions[] = [
                    'project' => $primaryProject->title,
                    'project_id' => $primaryProject->id,
                    'badge' => 'Tugas Prioritas',
                    'badge_color' => 'blue',
                    'action' => $uncompletedTask->task_name,
                    'phase' => ucfirst($uncompletedTask->phase),
                ];
            }

            if ($unbackedClaims > 0) {
                $nextActions[] = [
                    'project' => $primaryProject->title,
                    'project_id' => $primaryProject->id,
                    'badge' => 'Anti-Halusinasi',
                    'badge_color' => 'amber',
                    'action' => "Hubungkan {$unbackedClaims} klaim ilmiah dengan bukti (evidence) pendukung",
                    'phase' => 'Analysis & Claims',
                ];
            }

            $unwrittenSection = $primaryProject->paperSections()->where('is_drafted', false)->first();
            if ($unwrittenSection) {
                $nextActions[] = [
                    'project' => $primaryProject->title,
                    'project_id' => $primaryProject->id,
                    'badge' => 'Paper Builder',
                    'badge_color' => 'emerald',
                    'action' => "Draft section: {$unwrittenSection->title} dari data yang tersedia",
                    'phase' => 'Writing',
                ];
            }
        }

        // Research readiness summary
        $readiness = $primaryProject ? $primaryProject->readiness_breakdown : null;

        return view('dashboard', compact(
            'projects',
            'primaryProject',
            'recentIdeas',
            'recentEvidences',
            'totalIdeas',
            'totalProjects',
            'totalDatasets',
            'totalEvidences',
            'groundedClaims',
            'unbackedClaims',
            'totalOutputs',
            'publishedOutputs',
            'nextActions',
            'readiness'
        ));
    }
}
