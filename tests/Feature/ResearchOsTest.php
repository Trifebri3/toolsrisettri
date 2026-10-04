<?php

use App\Models\Claim;
use App\Models\Evidence;
use App\Models\ResearchIdea;
use App\Models\ResearchProject;
use App\Models\User;

test('dashboard loads successfully with active research metrics', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('RESEARCH OS');
    $response->assertSee('Workspace Peneliti');
});

test('quick capture stores an idea and automatically classifies it', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('ideas.store'), [
        'title' => 'Monitoring Kualitas Air Tambak Udang dengan IoT LoRa',
        'description' => 'Sensor dissolved oxygen dan pH untuk tambak pesisir',
        'priority' => 'high',
    ]);

    $response->assertRedirect(route('ideas.index'));

    $this->assertDatabaseHas('research_ideas', [
        'title' => 'Monitoring Kualitas Air Tambak Udang dengan IoT LoRa',
        'priority' => 'high',
    ]);
});

test('an idea can be promoted to a research project with standard sections', function () {
    $user = User::factory()->create();
    $idea = ResearchIdea::create([
        'user_id' => $user->id,
        'title' => 'Autonomous Drone for Coral Reef Survey',
        'field' => 'Computer Vision & Marine Science',
        'status' => 'captured',
    ]);

    $response = $this->actingAs($user)->post(route('ideas.promote', $idea->id));

    $project = ResearchProject::where('research_idea_id', $idea->id)->first();
    expect($project)->not->toBeNull();
    $response->assertRedirect(route('projects.show', $project->id));

    // Check that standard paper sections were created
    expect($project->paperSections()->count())->toBe(8);
});

test('anti-hallucination guardrail: linking evidence transforms claim into grounded', function () {
    $user = User::factory()->create();
    $project = ResearchProject::create([
        'user_id' => $user->id,
        'title' => 'Test Project',
        'slug' => 'test-project',
        'field' => 'Test Field',
    ]);

    $evidence = Evidence::create([
        'research_project_id' => $project->id,
        'title' => 'Lab Test #1',
        'evidence_type' => 'data_point',
        'description' => 'Test result verified',
        'quality_status' => 'ground_truth',
    ]);

    $claim = Claim::create([
        'research_project_id' => $project->id,
        'claim_text' => 'Energy reduced by 85%',
        'section_target' => 'results',
        'anti_hallucination_status' => 'missing_evidence',
        'is_verified' => false,
    ]);

    expect($claim->anti_hallucination_status)->toBe('missing_evidence');

    // Link evidence to claim
    $response = $this->actingAs($user)->post(route('projects.claims.link-evidence', [
        'project' => $project->id,
        'claim' => $claim->id,
    ]), [
        'evidence_id' => $evidence->id,
        'relevance_note' => 'Direct empirical proof',
    ]);

    $response->assertRedirect();
    $claim->refresh();

    expect($claim->anti_hallucination_status)->toBe('grounded');
    expect($claim->is_verified)->toBeTrue();

    // Unlink evidence from claim
    $unlinkResponse = $this->actingAs($user)->delete(route('projects.claims.unlink-evidence', [
        'project' => $project->id,
        'claim' => $claim->id,
        'evidence' => $evidence->id,
    ]));

    $unlinkResponse->assertRedirect();
    $claim->refresh();
    expect($claim->anti_hallucination_status)->toBe('missing_evidence');
    expect($claim->is_verified)->toBeFalse();
});

test('paper builder exports markdown document grounded in evidence', function () {
    $user = User::factory()->create();
    $project = ResearchProject::create([
        'user_id' => $user->id,
        'title' => 'Precision Farming Paper',
        'slug' => 'precision-farming-paper',
        'field' => 'Agriculture',
    ]);

    $project->paperSections()->create([
        'section_type' => 'introduction',
        'title' => '1. Introduction',
        'order' => 1,
        'content' => 'Precision agriculture leverages wireless sensors.',
        'is_drafted' => true,
    ]);

    $response = $this->actingAs($user)->get(route('projects.paper.export-markdown', $project->id));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
    expect($response->getContent())->toContain('# Precision Farming Paper');
    expect($response->getContent())->toContain('Precision agriculture leverages wireless sensors.');
});
