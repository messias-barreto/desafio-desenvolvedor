<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;

class ClientPassportSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::create([
            'id' => '9e1dca0f-8684-4b96-89e8-c8a5ad1d4eb3',
            'user_id' => null, 
            'name' => 'Cliente Teste', 
            'secret' => '41251dc3-5fed-44a3-bb73-a9d13b9e13fc',
            'provider' => 'users', 
            'redirect' => 'http://localhost',
            'personal_access_client' => true, 
            'password_client' => true, 
            'revoked' => false,
        ]);
    }
}
