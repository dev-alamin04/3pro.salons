<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('12345678');

        /**
         * ROLE => USER DATA
         */
        $roleUsers = [
            'admin'  => [
                'name'  => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '01521539767',
                'role'  => 'admin',
            ],
            'admin2' => [
                'name'  => 'alamin',
                'email' => 'mhalamin04@gmail.com',
                'phone' => '01521539767',
                'role'  => 'role',
            ],
            'user'   => [
                'name'  => 'User',
                'email' => 'user@gmail.com',
                'phone' => '01712345679',
                'role'  => 'user',
            ],
            'user2'  => [
                'name'  => 'alamin',
                'email' => 'alamin@gmail.com',
                'phone' => '01712345681',
                'role'  => 'user',
            ],
        ];

        foreach ($roleUsers as $roleName => $userData) {

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'phone'             => $userData['phone'],
                    'role'              => $userData['role'],
                    'password'          => $password,
                    'is_active'         => true,
                    'email_verified_at' => now(),
                    'term_accept'       => true,
                ]
            );

        }
    }
}
