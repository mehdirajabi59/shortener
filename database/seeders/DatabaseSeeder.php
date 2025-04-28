<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)
            ->has(Wallet::factory(1), 'wallet')
            ->has(Transaction::factory(2), 'transactions')
            ->create();
    }
}
