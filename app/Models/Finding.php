<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Finding extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'metrics_summary' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}
