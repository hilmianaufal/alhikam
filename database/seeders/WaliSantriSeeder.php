<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WaliSantriSeeder extends Seeder
{
    public function run(): void
    {
        $wali = User::firstOrCreate(
            ['email' => 'wali@alishlahpay.test'],
            [
                'name' => 'Wali Santri',
                'password' => Hash::make('password'),
            ]
        );

        $wali->assignRole('Wali Santri');
    }
}
