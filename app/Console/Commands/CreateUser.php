<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Digunakan untuk ngisi user ke table users secara random';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = Str::random(10).'@gmail.com';
        $password = Str::random(10);

        $user = User::create([
            'name' => Str::random(10),
            'email' => $email,
            'password' => Hash::make($password),
            'phone_number' => '0861231243',
            'role' => 'member'
        ]);

        return $this->info('Member user created. Credentials => Email: '.$email.' password: '.$password);
    }
}
