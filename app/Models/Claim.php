<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Claim extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    public function finding(): BelongsTo
    {
        return $this->belongsTo(Finding::class);
    }

    public function evidenceLinks(): HasMany
    {
        return $this->hasMany(ClaimEvidenceLink::class);
    }

    public function evidences(): BelongsToMany
    {
        return $this->belongsToMany(Evidence::class, 'claim_evidence_links', 'claim_id', 'evidence_id')
            ->withPivot('id', 'relevance_note')
            ->withTimestamps();
    }

    /**
     * Check anti-hallucination ground truth status
     */
    public function updateAntiHallucinationStatus(): string
    {
        $evidenceCount = $this->evidences()->count();
        if ($evidenceCount >= 1) {
            $status = 'grounded';
            $this->is_verified = true;
        } else {
            $status = 'missing_evidence';
            $this->is_verified = false;
        }
        $this->anti_hallucination_status = $status;
        $this->save();

        return $status;
    }
}
