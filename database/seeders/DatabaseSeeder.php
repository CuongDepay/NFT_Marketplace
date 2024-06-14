<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CollectionCategory;
use App\Models\ContactCategory;
use App\Models\NotificationCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        CollectionCategory::factory(10)->create();
        NotificationCategory::factory(5)->create();
        ContactCategory::factory(5)->create();
    }
}
