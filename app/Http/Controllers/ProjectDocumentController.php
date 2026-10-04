<?php

namespace App\Http\Controllers;

use App\Models\ProjectDocument;
use App\Models\ResearchProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectDocumentController extends Controller
{
    /**
     * Upload and store a new document for a research project.
     */
    public function store(Request $request, ResearchProject $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_category' => 'required|string|in:proposal,instrument,raw_data,photo_evidence,draft_report,literature,other',
            'file' => 'required|file|max:25600', // 25 MB max
            'description' => 'nullable|string|max:1000',
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $size = $uploadedFile->getSize();

        $path = $uploadedFile->store('documents/'.$project->id, 'public');

        ProjectDocument::create([
            'research_project_id' => $project->id,
            'user_id' => Auth::id() ?? $project->user_id,
            'title' => $validated['title'],
            'document_category' => $validated['document_category'],
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $size,
            'file_extension' => $extension,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Dokumen "'.$validated['title'].'" berhasil diunggah ke proyek!');
    }

    /**
     * Download the specified document file.
     */
    public function download(ResearchProject $project, ProjectDocument $document): StreamedResponse
    {
        if ($document->research_project_id !== $project->id) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * View the specified document inline (e.g., PDF or images).
     */
    public function view(ResearchProject $project, ProjectDocument $document): BinaryFileResponse
    {
        if ($document->research_project_id !== $project->id) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        return response()->file(Storage::disk('public')->path($document->file_path));
    }

    /**
     * Remove the specified document from storage and database.
     */
    public function destroy(ResearchProject $project, ProjectDocument $document): RedirectResponse
    {
        if ($document->research_project_id !== $project->id) {
            abort(404);
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'data'])
            ->with('success', 'Dokumen "'.$document->title.'" berhasil dihapus dari proyek.');
    }
}
