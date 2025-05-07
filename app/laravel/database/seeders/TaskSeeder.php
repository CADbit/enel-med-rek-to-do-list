<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tworzymy kilka zadań z różnymi statusami
        $tasks = [
            [
                'title' => 'Zaimplementować API',
                'description' => 'Stworzyć REST API dla zarządzania zadaniami',
                'status' => TaskStatus::COMPLETED->value,
            ],
            [
                'title' => 'Napisać testy jednostkowe',
                'description' => 'Przetestować wszystkie endpointy API',
                'status' => TaskStatus::COMPLETED->value,
            ],
            [
                'title' => 'Stworzyć frontend w Vue.js',
                'description' => 'Zaimplementować interfejs użytkownika',
                'status' => TaskStatus::WAITING->value,
            ],
            [
                'title' => 'Dodać walidację formularzy',
                'description' => 'Zaimplementować walidację po stronie frontendu',
                'status' => TaskStatus::PENDING->value,
            ],
            [
                'title' => 'Dodać obsługę błędów',
                'description' => 'Zaimplementować obsługę błędów API',
                'status' => TaskStatus::NEW->value,
            ],
            [
                'title' => 'Dodać filtrowanie zadań',
                'description' => 'Zaimplementować filtrowanie po statusie i dacie',
                'status' => TaskStatus::NEW->value,
            ],
            [
                'title' => 'Dodać sortowanie zadań',
                'description' => 'Zaimplementować sortowanie po różnych polach',
                'status' => TaskStatus::NEW->value,
            ],
            [
                'title' => 'Dodać paginację',
                'description' => 'Zaimplementować paginację wyników',
                'status' => TaskStatus::NEW->value,
            ],
            [
                'title' => 'Dodać wyszukiwanie',
                'description' => 'Zaimplementować wyszukiwanie w tytule i opisie',
                'status' => TaskStatus::STOP->value,
            ],
            [
                'title' => 'Dodać testy E2E',
                'description' => 'Napisać testy end-to-end dla całej aplikacji',
                'status' => TaskStatus::NEW->value,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }

        // Dodatkowo generujemy 5 losowych zadań używając fabryki
        Task::factory()->count(5)->create();
    }
}
