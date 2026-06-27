<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">← Projects</a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $project->name }}</h2>
            <span class="text-xs px-2 py-0.5 rounded-full
                {{ match($project->status) {
                    'active'    => 'bg-green-100 text-green-700',
                    'planning'  => 'bg-blue-100 text-blue-700',
                    'on_hold'   => 'bg-yellow-100 text-yellow-700',
                    'completed' => 'bg-gray-100 text-gray-600',
                    default     => 'bg-gray-100 text-gray-600',
                } }}">
                {{ str_replace('_', ' ', ucfirst($project->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Milestone summary --}}
            @if($project->milestones->isNotEmpty())
                <div class="mb-6 flex gap-3 overflow-x-auto pb-2">
                    @foreach($project->milestones as $milestone)
                        <div class="shrink-0 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3 min-w-40">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $milestone->title }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $milestone->tasks->where('status', 'done')->count() }}/{{ $milestone->tasks->count() }} done
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Kanban board --}}
            <livewire:projects.project-board :project="$project" />
        </div>
    </div>
</x-app-layout>
