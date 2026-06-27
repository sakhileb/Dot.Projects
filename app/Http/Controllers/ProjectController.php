<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show(Request $request, Project $project): \Illuminate\View\View
    {
        abort_unless(
            $request->user()->currentTeam->id === $project->team_id,
            403
        );

        $project->load(['owner', 'milestones.tasks', 'members']);

        return view('projects.show', compact('project'));
    }
}
