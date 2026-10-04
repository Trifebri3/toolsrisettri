<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dataset extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
        'collection_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }
}
