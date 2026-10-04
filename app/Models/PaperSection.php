<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'missing_evidence_flags' => 'array',
        'is_drafted' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }
}
