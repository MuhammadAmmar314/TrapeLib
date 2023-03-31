<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 5; $i++) {
            $transaction = new Transaction;

            $transaction->member_id = rand(1, 20);
            $transaction->date_start = '2023-03-02';
            $transaction->date_end = '2023-03-10';
            $transaction->status = rand(0,1);
            $transaction->book_id = rand(1, 20);

            $transaction->save();
        }
    }
}
