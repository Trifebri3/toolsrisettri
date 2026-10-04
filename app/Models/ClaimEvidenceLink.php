<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimEvidenceLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function evidence(): BelongsTo
    {
        return $this->belongsTo(Evidence::class);
    }
}
