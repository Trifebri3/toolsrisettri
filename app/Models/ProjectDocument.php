<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_project_id',
        'user_id',
        'title',
        'document_category',
        'file_path',
        'file_name',
        'file_size',
        'file_extension',
        'description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'research_project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0).' KB';
        }

        return $bytes.' B';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->document_category) {
            'proposal' => 'Proposal & Etik',
            'instrument' => 'Instrumen / Kuesioner',
            'raw_data' => 'Raw Data / Spreadsheet',
            'photo_evidence' => 'Foto / Bukti Lapangan',
            'draft_report' => 'Draf & Laporan Riset',
            'literature' => 'Referensi Jurnal',
            default => 'Dokumen Umum',
        };
    }

    public function getCategoryBadgeAttribute(): array
    {
        return match ($this->document_category) {
            'proposal' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
            'instrument' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-800', 'border' => 'border-indigo-200'],
            'raw_data' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-800', 'border' => 'border-teal-200'],
            'photo_evidence' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
            'draft_report' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-800', 'border' => 'border-sky-200'],
            'literature' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'border' => 'border-purple-200'],
            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
        };
    }
}
