<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Research OS Academic Manuscript</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=eb-garamond:400,500,600,700|inter:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 10.5pt; color: #000; background: #fff !important; }
            .page-break { page-break-before: always; }
        }
        .serif-text { font-family: 'EB Garamond', Georgia, serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen py-8">

    <!-- Top Floating Toolbar (No-Print) -->
    <div class="no-print max-w-4xl mx-auto px-4 mb-6 flex items-center justify-between">
        <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'paper']) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
            ← Kembali ke Workspace Paper
        </a>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-slate-500">Academic Paper Preview</span>
            <button onclick="window.print()" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition flex items-center space-x-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Paper Sheet Container (Standard Academic Paper Style) -->
    <article class="max-w-4xl mx-auto bg-white p-10 sm:p-16 rounded-xl shadow-lg border border-slate-200 serif-text leading-relaxed text-[16px]">

        <!-- Paper Header -->
        <header class="text-center space-y-4 pb-8 border-b border-slate-200">
            <div class="text-[12px] font-sans font-bold tracking-widest text-slate-400 uppercase">
                Research OS Grounded Manuscript • {{ $project->field }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-snug">
                {{ $project->title }}
            </h1>
            <div class="font-sans text-sm font-semibold text-slate-700">
                {{ $project->user->name ?? 'Dr. Researcher, M.Eng.' }}
            </div>
            <div class="font-sans text-xs text-slate-500">
                Department of Engineering & Agricultural Technology • Research Repository ID: RS-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
            </div>
        </header>

        <!-- Paper Sections Stream -->
        <div class="mt-8 space-y-8">
            @foreach ($project->paperSections as $section)
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-1 {{ $section->section_type === 'abstract' ? 'italic text-center border-0' : '' }}">
                        {{ $section->title }}
                    </h2>

                    @if ($section->section_type === 'abstract')
                        <div class="bg-slate-50/70 p-5 rounded-lg border border-slate-100 italic text-[15px] leading-relaxed text-slate-800">
                            {!! nl2br(e($section->content ?? 'Abstract not yet drafted.')) !!}
                        </div>
                    @else
                        <div class="text-slate-800 whitespace-pre-line leading-relaxed text-justify">
                            {{ $section->content ?? '[Section content is not yet written]' }}
                        </div>
                    @endif
                </section>
            @endforeach

            <!-- Grounded Evidence Backlinks Appendix (Research OS Scientific Transparency Guarantee) -->
            <section class="pt-8 border-t border-slate-200 space-y-3 font-sans text-xs">
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-0.5 rounded bg-teal-100 text-teal-800 font-bold uppercase tracking-wider text-[10px]">
                        Scientific Evidence Traceability Appendix
                    </span>
                </div>
                <p class="text-slate-500 leading-normal">
                    This manuscript was composed using <strong>Research OS Anti-Hallucination Framework</strong>. Every numerical metric and empirical claim in the Results & Discussion sections maps directly to verified records:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                    @foreach ($project->claims->where('anti_hallucination_status', 'grounded') as $c)
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                            <span class="font-bold text-slate-900">Claim:</span> "{{ Str::limit($c->claim_text, 80) }}"<br>
                            <span class="text-teal-700 font-semibold">Backed by:</span>
                            @foreach ($c->evidences as $e)
                                <span class="underline">[{{ $e->evidence_type }}] {{ $e->title }}</span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

    </article>

</body>
</html>
