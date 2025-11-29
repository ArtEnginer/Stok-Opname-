<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Administrator',
            'description' => 'Full access to all features including master data management.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'Can only view and edit stock opname items.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        // Admin permissions
        'admin.access'        => 'Can access admin area',
        'admin.settings'      => 'Can manage site settings',

        // User management
        'users.manage'        => 'Can manage users',

        // Location management
        'locations.manage'    => 'Can manage locations/racks',

        // Product management
        'products.manage'     => 'Can manage products',

        // Stock Opname permissions
        'stockopname.create'  => 'Can create stock opname sessions',
        'stockopname.edit'    => 'Can edit stock opname items',
        'stockopname.close'   => 'Can close/reopen stock opname sessions',
        'stockopname.delete'  => 'Can delete stock opname sessions',

        // Transaction management
        'transactions.manage' => 'Can manage transactions',

        // Reports
        'reports.view'        => 'Can view reports',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'admin' => [
            'admin.access',
            'admin.settings',
            'users.manage',
            'locations.manage',
            'products.manage',
            'stockopname.create',
            'stockopname.edit',
            'stockopname.close',
            'stockopname.delete',
            'transactions.manage',
            'reports.view',
        ],
        'user' => [
            'stockopname.edit',
            'reports.view',
        ],
    ];
}
