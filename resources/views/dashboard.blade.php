<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Projects
            </h2>
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                + New Project
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($projects->isEmpty())
                <div class="text-center py-20">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">No projects yet.</p>
                    <a href="{{ route('projects.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                        Create your first project
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <a href="{{ route('projects.show', $project) }}"
                           class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $project->name }}</h3>
                                <span class="ml-2 shrink-0 text-xs px-2 py-0.5 rounded-full
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
                            @if($project->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">{{ $project->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $project->tasks_count }} tasks</span>
                                <span>{{ $project->done_tasks_count }}/{{ $project->tasks_count }} done</span>
                            </div>
                            @if($project->tasks_count > 0)
                                <div class="mt-2 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500 rounded-full transition-all"
                                         style="width: {{ ($project->done_tasks_count / $project->tasks_count) * 100 }}%"></div>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
