<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class GetUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:user {user_id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ini di pakai untuk mendapatkan data user dari DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            return $this->error('User data not found!');
        }

        return $this->info('Name: '.$user->name);
    }
}
