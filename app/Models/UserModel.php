<?php

declare (strict_types = 1);

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use Faker\Generator;

class UserModel extends ShieldUserModel
{
    protected $returnType = User::class;

    protected function initialize(): void
    {
        parent::initialize();
        $this->allowedFields = [
             ...$this->allowedFields,
            'name',
            'picture',
            'email',
        ];
    }

    public function fake(Generator &$faker): User
    {
        return new $this->returnType([
            'username' => $faker->unique()->userName(),
            'active' => true,
        ]);
    }
}
