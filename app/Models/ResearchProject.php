<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'target_outputs' => 'array',
        'target_deadline' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function idea(): BelongsTo
    {
        return $this->belongsTo(ResearchIdea::class, 'research_idea_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ResearchQuestion::class)->orderBy('order');
    }

    public function literatures(): HasMany
    {
        return $this->hasMany(Literature::class);
    }

    public function datasets(): HasMany
    {
        return $this->hasMany(Dataset::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function paperSections(): HasMany
    {
        return $this->hasMany(PaperSection::class)->orderBy('order');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(ResearchOutput::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ResearchTask::class)->orderBy('order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class)->latest();
    }

    /**
     * Compute dynamic research readiness breakdown & overall percentage
     */
    public function getReadinessBreakdownAttribute(): array
    {
        // 1. Foundation: Problem, Gap, Novelty, Research Questions (max 20 pts)
        $foundationScore = 0;
        if (! empty($this->problem_statement)) {
            $foundationScore += 5;
        }
        if (! empty($this->research_gap)) {
            $foundationScore += 5;
        }
        if (! empty($this->novelty)) {
            $foundationScore += 5;
        }
        if ($this->questions()->count() > 0) {
            $foundationScore += 5;
        }

        // 2. Data & Evidence: Datasets & Evidences logged (max 20 pts)
        $dataScore = 0;
        $datasetCount = $this->datasets()->count();
        $evidenceCount = $this->evidences()->count();
        if ($datasetCount > 0) {
            $dataScore += 10;
        }
        if ($evidenceCount >= 1) {
            $dataScore += 5;
        }
        if ($evidenceCount >= 3) {
            $dataScore += 5;
        }

        // 3. Methodology: Design, Variables, Instruments, Metrics (max 20 pts)
        $methodScore = 0;
        if (! empty($this->methodology_design)) {
            $methodScore += 5;
        }
        if (! empty($this->variables)) {
            $methodScore += 5;
        }
        if (! empty($this->instruments)) {
            $methodScore += 5;
        }
        if (! empty($this->evaluation_metrics)) {
            $methodScore += 5;
        }

        // 4. Analysis & Claims: Findings & Verified Claims with evidence (max 20 pts)
        $analysisScore = 0;
        $findingsCount = $this->findings()->count();
        $claimsCount = $this->claims()->count();
        $groundedClaims = $this->claims()->where('anti_hallucination_status', 'grounded')->count();

        if ($findingsCount > 0) {
            $analysisScore += 8;
        }
        if ($claimsCount > 0) {
            $analysisScore += 6;
        }
        if ($groundedClaims > 0) {
            $analysisScore += 6;
        }

        // 5. Paper Drafting & Output Readiness: Sections written (max 20 pts)
        $writingScore = 0;
        $draftedSections = $this->paperSections()->where('is_drafted', true)->count();
        $writingScore = min(20, $draftedSections * 3);

        $totalScore = $foundationScore + $dataScore + $methodScore + $analysisScore + $writingScore;

        return [
            'foundation' => ['score' => $foundationScore, 'max' => 20, 'pct' => round(($foundationScore / 20) * 100)],
            'data' => ['score' => $dataScore, 'max' => 20, 'pct' => round(($dataScore / 20) * 100)],
            'methodology' => ['score' => $methodScore, 'max' => 20, 'pct' => round(($methodScore / 20) * 100)],
            'analysis' => ['score' => $analysisScore, 'max' => 20, 'pct' => round(($analysisScore / 20) * 100)],
            'writing' => ['score' => $writingScore, 'max' => 20, 'pct' => round(($writingScore / 20) * 100)],
            'overall' => min(100, $totalScore),
        ];
    }
}
