<?php

namespace Database\Seeders;

use App\Models\{User, Category, Ticket, Comment};
use Illuminate\Database\Seeder;

class HelpdeskSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 10 user terlebih dahulu
        $users = User::factory()->count(10)->create();

        // Membuat 3 kategori
        $categories = collect(['Akun', 'Jaringan', 'Aplikasi'])
            ->map(fn ($name) => Category::factory()->create([
                'name' => $name
            ]));

        // Membuat 50 tiket menggunakan user dan kategori yang sudah ada
        Ticket::factory()->count(50)
            ->recycle($users)
            ->recycle($categories)
            ->create()
            ->each(function ($ticket) use ($users) {

                // Setiap tiket mendapatkan 2 komentar
                Comment::factory()->count(2)
                    ->for($ticket)
                    ->recycle($users)
                    ->create();
            });
    }
}