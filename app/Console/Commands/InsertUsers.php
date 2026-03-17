<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class InsertUsers extends Command
{
    protected $signature = 'users:insert';
    protected $description = 'Insert 10 lakh users efficiently';

    public function handle()
    {
        $total = 1000000;
        $chunkSize = 1000;

        for ($i = 0; $i < $total; $i += $chunkSize) {

            $users = [];

            for ($j = 0; $j < $chunkSize; $j++) {
                $count = $i + $j + 1;

                $name = "Akshay" . $count;

                $users[] = [
                    'name' => $name,
                    'email' => $name . '@gmail.com',
                ];
            }

            User::insert($users);

            $this->info("Inserted: " . ($i + $chunkSize));
        }

        $this->info("Done inserting 10 lakh users!");
    }
}
