<?php

namespace Database\Seeders;

use App\Models\Tags;
use App\Models\Project;
use Database\Factories\TagsFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tags::factory()->count(3)->create();

        Project::factory()->count(20)->hasAttached(
            Tags::inRandomOrder()->get()
        )->create();
    }
}
