<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = model('UserModel');

        // Check if admin exists
        $existingAdmin = $users->where('username', 'admin')->first();

        if (!$existingAdmin) {
            // Create admin user
            $user = new User([
                'username' => 'admin',
                'email'    => 'admin@stockopname.com',
                'password' => 'admin123',
                'active'   => true,
            ]);

            $users->save($user);

            // Get the user ID
            $userEntity = $users->findById($users->getInsertID());

            // Add to admin group
            $userEntity->addGroup('admin');

            echo "Admin user created successfully!\n";
            echo "Username: admin\n";
            echo "Email: admin@stockopname.com\n";
            echo "Password: admin123\n";
            echo "Role: Administrator\n";
        } else {
            echo "Admin user already exists!\n";
            echo "Username: admin\n";
            echo "Password: (use existing password or reset if needed)\n";
        }

        // Check if regular user exists
        $existingUser = $users->where('username', 'user')->first();

        if (!$existingUser) {
            // Create regular user
            $regularUser = new User([
                'username' => 'user',
                'email'    => 'user@stockopname.com',
                'password' => 'user123',
                'active'   => true,
            ]);

            $users->save($regularUser);

            // Get the user ID
            $regularUserEntity = $users->findById($users->getInsertID());

            // Add to user group
            $regularUserEntity->addGroup('user');

            echo "\nRegular user created successfully!\n";
            echo "Username: user\n";
            echo "Email: user@stockopname.com\n";
            echo "Password: user123\n";
            echo "Role: User\n";
        } else {
            echo "\nRegular user already exists!\n";
            echo "Username: user\n";
            echo "Password: (use existing password or reset if needed)\n";
        }
    }
}
