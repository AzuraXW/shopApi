<?php

namespace Database\Seeders;

use App\Models\Comments;
use App\Models\Slides;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comments::factory()->count(100)->create();
    }
}
