<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('CategorySeeder');
        $this->call('CampaignSeeder');

        echo "\n✅ Database seeded successfully!\n";
        echo "   - Categories: 6 items\n";
        echo "   - Campaigns: 5 items\n";
        echo "\n🚀 You can now access the application at: http://localhost:8080\n";
        echo "📊 Admin panel: http://localhost:8080/admin\n\n";
    }
}
