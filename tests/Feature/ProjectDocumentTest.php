<?php

use App\Models\ProjectDocument;
use App\Models\ResearchProject;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('default user trifebriansah321@gmail.com authenticates with password 12344321', function () {
    $user = User::factory()->create([
        'email' => 'trifebriansah321@gmail.com',
        'password' => Hash::make('12344321'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'trifebriansah321@gmail.com',
        'password' => '12344321',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('user can upload a research document to project', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $project = ResearchProject::create([
        'user_id' => $user->id,
        'title' => 'Soil Monitoring Project',
        'slug' => 'soil-monitoring-project',
        'field' => 'Computer Science & IoT',
        'status' => 'data_collection',
    ]);

    $file = UploadedFile::fake()->create('proposal_hibah_2026.pdf', 1024, 'application/pdf');

    $response = $this->actingAs($user)->post(route('projects.documents.store', $project->id), [
        'title' => 'Proposal Riset Hibah Kemendikbud 2026',
        'document_category' => 'proposal',
        'file' => $file,
        'description' => 'Dokumen usulan penelitian dan ethical clearance.',
    ]);

    $response->assertRedirect(route('projects.show', ['project' => $project->id, 'tab' => 'data']));

    $this->assertDatabaseHas('project_documents', [
        'research_project_id' => $project->id,
        'title' => 'Proposal Riset Hibah Kemendikbud 2026',
        'document_category' => 'proposal',
        'file_name' => 'proposal_hibah_2026.pdf',
        'file_extension' => 'pdf',
    ]);

    $doc = ProjectDocument::first();
    Storage::disk('public')->assertExists($doc->file_path);
});

test('user can download uploaded project document', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $project = ResearchProject::create([
        'user_id' => $user->id,
        'title' => 'Soil Monitoring Project 2',
        'slug' => 'soil-monitoring-project-2',
        'field' => 'Computer Science & IoT',
        'status' => 'data_collection',
    ]);

    $file = UploadedFile::fake()->create('raw_sensor_data.csv', 500, 'text/csv');
    $path = $file->store('documents/'.$project->id, 'public');

    $doc = ProjectDocument::create([
        'research_project_id' => $project->id,
        'user_id' => $user->id,
        'title' => 'Raw Sensor Data CSV',
        'document_category' => 'raw_data',
        'file_path' => $path,
        'file_name' => 'raw_sensor_data.csv',
        'file_size' => 500 * 1024,
        'file_extension' => 'csv',
    ]);

    $response = $this->actingAs($user)->get(route('projects.documents.download', ['project' => $project->id, 'document' => $doc->id]));

    $response->assertOk();
    $response->assertDownload('raw_sensor_data.csv');
});

test('user can delete project document', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $project = ResearchProject::create([
        'user_id' => $user->id,
        'title' => 'Soil Monitoring Project 3',
        'slug' => 'soil-monitoring-project-3',
        'field' => 'Computer Science & IoT',
        'status' => 'data_collection',
    ]);

    $file = UploadedFile::fake()->create('draft_to_delete.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    $path = $file->store('documents/'.$project->id, 'public');

    $doc = ProjectDocument::create([
        'research_project_id' => $project->id,
        'user_id' => $user->id,
        'title' => 'Draft To Delete',
        'document_category' => 'draft_report',
        'file_path' => $path,
        'file_name' => 'draft_to_delete.docx',
        'file_size' => 200 * 1024,
        'file_extension' => 'docx',
    ]);

    Storage::disk('public')->assertExists($path);

    $response = $this->actingAs($user)->delete(route('projects.documents.destroy', ['project' => $project->id, 'document' => $doc->id]));

    $response->assertRedirect(route('projects.show', ['project' => $project->id, 'tab' => 'data']));
    $this->assertDatabaseMissing('project_documents', ['id' => $doc->id]);
    Storage::disk('public')->assertMissing($path);
});
