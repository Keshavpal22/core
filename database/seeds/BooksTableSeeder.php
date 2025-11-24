<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;      // <-- DB import kiya
use Faker\Factory as Faker;             // <-- Faker import kiya

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();       // Aapne ab class alias use kiya hai

        for ($i = 0; $i < 5000; $i++) {
            DB::table('books')->insert([
                'title' => $faker->sentence(3),
                'author' => $faker->name,
                'genre' => $faker->word,
                'publisher' => $faker->company,
                'publication_year' => $faker->year,
                'total_copies' => $faker->numberBetween(1, 100),
                'available_copies' => $faker->numberBetween(0, 100),
                'isbn' => $faker->unique()->numberBetween(1000000000, 9999999999),
                // aur columns ho to unko bhi yahan add karo
            ]);
        }
    }
}
