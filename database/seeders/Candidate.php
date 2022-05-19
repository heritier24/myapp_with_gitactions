<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\candidateusers;
use Illuminate\Support\Facades\Hash;

class Candidate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = 'Danielson4';
        candidateusers::create([
            'name' => 'Deogratias NIYITANGA',
            'email' => '07868497493',
            'password' => Hash::make($password)
        ]);
    }
}
