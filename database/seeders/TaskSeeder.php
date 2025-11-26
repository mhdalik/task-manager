<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Finish project report',
                'description' => 'Complete the quarterly project summary report.',
                'is_completed' => false,
                'due_date' => '2025-02-10',
            ],
            [
                'title' => 'Team meeting',
                'description' => 'Discuss upcoming sprint tasks.',
                'is_completed' => false,
                'due_date' => '2025-02-12',
            ],
            [
                'title' => 'Update documentation',
                'description' => 'Revise API documentation for new endpoints.',
                'is_completed' => false,
                'due_date' => '2025-02-15',
            ],
            [
                'title' => 'Code review',
                'description' => 'Review merge requests from the dev team.',
                'is_completed' => false,
                'due_date' => '2025-02-11',
            ],
            [
                'title' => 'Database backup',
                'description' => 'Perform scheduled database backup.',
                'is_completed' => true,
                'due_date' => '2025-02-05',
            ],
            [
                'title' => 'Prepare presentation',
                'description' => 'Create slides for Mondays meeting.',
                'is_completed' => false,
                'due_date' => '2025-02-09',
            ],
            [
                'title' => 'Client follow-up',
                'description' => 'Send updated contract draft to client.',
                'is_completed' => true,
                'due_date' => '2025-02-06',
            ],
        ];

        foreach ($tasks as $task) {
            Task::updateOrCreate(['title' => $task['title']], $task);
        }
    }
}
