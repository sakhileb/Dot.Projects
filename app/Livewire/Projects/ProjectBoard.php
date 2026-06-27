<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectTask;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProjectBoard extends Component
{
    public Project $project;

    public string $activeStatus = 'all';

    public ?int $movingTaskId = null;

    public const COLUMNS = [
        'backlog'     => 'Backlog',
        'todo'        => 'To Do',
        'in_progress' => 'In Progress',
        'review'      => 'Review',
        'done'        => 'Done',
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    #[Computed]
    public function tasksByStatus(): array
    {
        $tasks = $this->project->tasks()
            ->with(['assignee', 'milestone'])
            ->orderBy('sort_order')
            ->get()
            ->groupBy('status');

        $columns = [];
        foreach (array_keys(self::COLUMNS) as $status) {
            $columns[$status] = $tasks->get($status, collect());
        }

        return $columns;
    }

    public function moveTask(int $taskId, string $newStatus): void
    {
        $task = ProjectTask::findOrFail($taskId);
        abort_unless($task->project_id === $this->project->id, 403);

        $task->update(['status' => $newStatus]);
        unset($this->tasksByStatus);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.projects.project-board');
    }
}
