<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidences';

    protected $guarded = [];

    protected $casts = [
        'data_payload' => 'array',
        'collected_at' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function claimLinks(): HasMany
    {
        return $this->hasMany(ClaimEvidenceLink::class);
    }

    public function claims(): BelongsToMany
    {
        return $this->belongsToMany(Claim::class, 'claim_evidence_links', 'evidence_id', 'claim_id')
            ->withPivot('relevance_note')
            ->withTimestamps();
    }
}
