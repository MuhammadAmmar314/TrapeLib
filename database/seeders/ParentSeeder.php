<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Parents::factory(5)->create()->each( function (\App\Models\Parents $parent) {
            \App\Models\Child::factory(2)->state([
                'parents_id' => $parent->id,
                'book_id' => \App\Models\Book::inRandomOrder()->first()->id,
                'qty' => 1
            ])->create();
        });
    }
}
