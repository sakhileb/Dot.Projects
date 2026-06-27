<?php

namespace App\Livewire\Projects;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Services\AiProjectPlannerService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProject extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('required|in:planning,active,on_hold,completed')]
    public string $status = 'planning';

    #[Validate('nullable|date')]
    public string $startDate = '';

    #[Validate('nullable|date|after_or_equal:startDate')]
    public string $dueDate = '';

    public bool $generating = false;
    public ?string $aiError = null;

    public function save(): void
    {
        $this->validate();

        $project = Project::create([
            'team_id'    => auth()->user()->currentTeam->id,
            'owner_id'   => auth()->id(),
            'name'       => $this->name,
            'description'=> $this->description ?: null,
            'status'     => $this->status,
            'start_date' => $this->startDate ?: null,
            'due_date'   => $this->dueDate ?: null,
        ]);

        $this->redirect(route('projects.show', $project));
    }

    public function generatePlan(): void
    {
        $this->validate();

        $this->generating = true;
        $this->aiError    = null;

        $project = Project::create([
            'team_id'    => auth()->user()->currentTeam->id,
            'owner_id'   => auth()->id(),
            'name'       => $this->name,
            'description'=> $this->description ?: null,
            'status'     => $this->status,
            'start_date' => $this->startDate ?: null,
            'due_date'   => $this->dueDate ?: null,
        ]);

        $service = app(AiProjectPlannerService::class);
        $plan    = $service->generatePlan($project, auth()->id());

        if ($plan === null) {
            $this->aiError    = 'AI planning failed. The project was created without a plan.';
            $this->generating = false;
            $this->redirect(route('projects.show', $project));
            return;
        }

        foreach ($plan['milestones'] ?? [] as $i => $ms) {
            $milestone = Milestone::create([
                'project_id'  => $project->id,
                'title'       => $ms['title'],
                'description' => $ms['description'] ?? null,
                'sort_order'  => $i,
            ]);

            foreach ($ms['tasks'] ?? [] as $j => $t) {
                ProjectTask::create([
                    'project_id'   => $project->id,
                    'milestone_id' => $milestone->id,
                    'title'        => $t['title'],
                    'priority'     => $t['priority'] ?? 'medium',
                    'status'       => 'backlog',
                    'sort_order'   => $j,
                ]);
            }
        }

        $this->generating = false;
        $this->redirect(route('projects.show', $project));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.projects.create-project');
    }
}
