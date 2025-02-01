<?php

namespace Database\Seeders;

use App\Models\UploadFileStatus;
use App\UploadFileStatusEnum;
use Illuminate\Database\Seeder;

class UploadFileStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UploadFileStatusEnum::cases() as $status) {
            UploadFileStatus::create([
                'name'    => $status->value,
                'description' => $status->message()
            ]);
        }
    }
}
