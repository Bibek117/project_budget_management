<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Project;
use App\Models\Timeline;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //projects
        $projects = [
            ['title'=>'Education for Rural Nepal','desc'=>'This is a campaign to educate people all over rural nepal','start_date'=>'2024-04-19','end_date'=>'2024-12-29'],
            ['title'=>'Women Empowerment','desc'=>'This is a campaign to empower women','start_date'=>'2025-01-01','end_date'=>'2025-11-13'],
            ['title'=>'Light up Nepal','desc'=>'This is a campaign to provide solar energy to all remote places where electricity is not reached','start_date'=>'2026-01-01','end_date'=>'2026-12-01'],
        ];

        Project::insert($projects);


        //timelines
        $timelines = [
            ['project_id'=>1,'title'=>'phase1', 'start_date' => '2024-04-20', 'end_date' => '2024-07-29'],
            ['project_id' => 1,'title'=>'phase2', 'start_date' => '2024-08-01', 'end_date' => '2024-10-22'],
            ['project_id' => 1,'title'=>'phase3', 'start_date' => '2024-10-23', 'end_date' => '2024-12-28'],
            ['project_id'=>2, 'title' => 'start', 'start_date' => '2025-01-01', 'end_date' => '2025-04-13'],
            ['project_id'=>2, 'title' => 'middle','start_date' => '2025-04-14', 'end_date' => '2025-08-28'],
            ['project_id'=>2, 'title' => 'end','start_date' => '2025-08-30', 'end_date' => '2025-11-13'],
            ['project_id'=>3, 'title' => 'Case Study Phase', 'start_date' => '2026-01-01', 'end_date' => '2026-03-01'],
            ['project_id'=>3, 'title' => 'Material Supply phase','start_date' => '2026-03-02', 'end_date' => '2026-06-01'],
            ['project_id'=>3, 'title' => 'INtegrationa and building','start_date' => '2026-06-02', 'end_date' => '2026-12-01']
        ];

        Timeline::insert($timelines);

        $budgets = [
            ['timeline_id' => 1, 'name' => 'Education Material', 'amount' => 5000.00],
            ['timeline_id' => 1, 'name' => 'Training Expenses', 'amount' => 3000.00],
            ['timeline_id' => 1, 'name' => 'Infrastructure Development', 'amount' => 7000],
            ['timeline_id' => 2, 'name' => 'Workshops', 'amount' => 4000.00],
            ['timeline_id' => 2, 'name' => 'Awareness Programs', 'amount' => 6000.00],
            ['timeline_id' => 2, 'name' => 'Skill Development', 'amount' => 5000.00],
            ['timeline_id' => 3, 'name' => 'Solar Panels', 'amount' => 8000.00],
            ['timeline_id'=>3,'name'=>'Battery Storage','amount'=>500],
            ['timeline_id' => 3, 'name' => 'Installation Costs', 'amount' => 6000.00],
            ['timeline_id' => 4, 'name' => 'Workshops', 'amount' => 3500.00],
            ['timeline_id' => 4, 'name' => 'Skill Development', 'amount' => 4500.00],
            ['timeline_id' => 4, 'name' => 'Awareness Programs', 'amount' => 4000.00],
            ['timeline_id' => 5, 'name' => 'Solar Panels', 'amount' => 7500.00],
            ['timeline_id' => 5, 'name' => 'Battery Storage', 'amount' => 4500.00],
            ['timeline_id' => 5, 'name' => 'Installation Costs', 'amount' => 5500.00],
            ['timeline_id' => 6, 'name' => 'Research & Development', 'amount' => 6000.00],
            ['timeline_id' => 6, 'name' => 'Material Procurement', 'amount' => 7000.00],
            ['timeline_id' => 6, 'name' => 'Testing & Integration', 'amount' => 8000.00],
            ['timeline_id' => 7, 'name' => 'Research & Study Material', 'amount' => 5000.00],
            ['timeline_id' => 7, 'name' => 'Prototype Development', 'amount' => 6500.00],
            ['timeline_id' => 7, 'name' => 'Implementation & Testing', 'amount' => 7500.00],
            ['timeline_id' => 8, 'name' => 'Education Material', 'amount' => 5000.00],
            ['timeline_id' => 8, 'name' => 'Training Expenses', 'amount' => 3000.00],
            ['timeline_id' => 8, 'name' => 'Infrastructure Development', 'amount' => 7000.00],
            ['timeline_id' => 9, 'name' => 'Workshops', 'amount' => 4000.00],
            ['timeline_id' => 9, 'name' => 'Awareness Programs', 'amount' => 6000.00],
        ];

        Budget::insert($budgets);
    }
}
