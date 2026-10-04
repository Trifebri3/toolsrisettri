<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Services\AiResearchService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(protected AiResearchService $aiService) {}

    public function query(Request $request, ResearchProject $project)
    {
        $mode = $request->input('mode', 'research_mapper');

        $result = match ($mode) {
            'research_mapper' => $this->aiService->runResearchMapper($project),
            'literature_assistant' => $this->aiService->runLiteratureAssistant($project),
            'methodology_assistant' => $this->aiService->runMethodologyAssistant($project),
            'data_assistant' => $this->aiService->runDataAssistant($project),
            'reviewer_mode' => $this->aiService->runReviewerAudit($project),
            default => $this->aiService->runResearchMapper($project),
        };

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'ai', 'ai_mode' => $mode])
            ->with('ai_result', $result);
    }
}
