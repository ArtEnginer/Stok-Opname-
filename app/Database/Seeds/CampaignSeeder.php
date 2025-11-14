<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'category_id' => 1, // Kesehatan
                'title' => 'Bantu Adik Rina Melawan Kanker Darah',
                'slug' => 'bantu-adik-rina-melawan-kanker-darah-' . time(),
                'short_description' => 'Adik Rina (8 tahun) membutuhkan biaya pengobatan kanker darah sebesar Rp 200 juta',
                'description' => "Adik Rina adalah seorang anak berusia 8 tahun yang sangat ceria dan pintar. Namun, takdir berkata lain ketika ia didiagnosa menderita Leukemia (kanker darah) pada bulan Januari 2024.\n\nKondisi Rina semakin memburuk dan membutuhkan pengobatan intensif berupa kemoterapi dan transplantasi sumsum tulang. Total biaya yang dibutuhkan mencapai Rp 200.000.000.\n\nOrangtua Rina hanyalah buruh pabrik dengan penghasilan pas-pasan. Mereka sudah menjual semua yang mereka miliki namun masih belum cukup untuk biaya pengobatan.\n\nMari kita bantu Adik Rina untuk mendapatkan pengobatan yang layak dan kembali bermain seperti anak seusianya.",
                'target_amount' => 200000000,
                'collected_amount' => 45000000,
                'donor_count' => 234,
                'image' => null,
                'start_date' => date('Y-m-d', strtotime('-30 days')),
                'end_date' => date('Y-m-d', strtotime('+60 days')),
                'status' => 'active',
                'is_featured' => 1,
                'is_urgent' => 1,
                'organizer_name' => 'Yayasan Peduli Anak Indonesia',
                'organizer_phone' => '081234567890',
                'organizer_email' => 'ypai@example.com',
                'views' => 5432,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 2, // Pendidikan
                'title' => 'Beasiswa untuk 100 Anak Kurang Mampu',
                'slug' => 'beasiswa-untuk-100-anak-kurang-mampu-' . time(),
                'short_description' => 'Program beasiswa pendidikan untuk 100 anak dari keluarga prasejahtera',
                'description' => "Indonesia memiliki banyak anak-anak cerdas yang terpaksa putus sekolah karena keterbatasan biaya. Melalui program ini, kami ingin memberikan beasiswa pendidikan kepada 100 anak dari keluarga kurang mampu.\n\nBeasiswa ini akan mencakup:\n- Biaya sekolah selama 1 tahun\n- Seragam dan perlengkapan sekolah\n- Buku pelajaran\n- Uang saku bulanan\n\nDengan target dana Rp 500 juta, setiap anak akan mendapat bantuan senilai Rp 5 juta per tahun.\n\nMari bersama-sama memberikan kesempatan kepada anak-anak Indonesia untuk meraih mimpi mereka melalui pendidikan!",
                'target_amount' => 500000000,
                'collected_amount' => 185000000,
                'donor_count' => 876,
                'image' => null,
                'start_date' => date('Y-m-d', strtotime('-20 days')),
                'end_date' => date('Y-m-d', strtotime('+70 days')),
                'status' => 'active',
                'is_featured' => 1,
                'is_urgent' => 0,
                'organizer_name' => 'Komunitas Pendidikan Indonesia',
                'organizer_phone' => '081234567891',
                'organizer_email' => 'kpi@example.com',
                'views' => 8234,
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 3, // Bencana Alam
                'title' => 'Bantu Korban Banjir Bandang di Sumatra',
                'slug' => 'bantu-korban-banjir-bandang-sumatra-' . time(),
                'short_description' => 'Ribuan warga kehilangan rumah akibat banjir bandang, mari kita ulurkan tangan',
                'description' => "Banjir bandang yang terjadi di Sumatra telah menghancurkan ratusan rumah dan merenggut harta benda ribuan warga. Mereka kini mengungsi dan membutuhkan bantuan segera.\n\nBantuan yang dibutuhkan:\n- Makanan dan air bersih\n- Pakaian dan selimut\n- Obat-obatan\n- Tenda darurat\n- Biaya pembangunan rumah sementara\n\nSetiap donasi Anda akan sangat berarti bagi mereka yang kehilangan segalanya. Mari kita tunjukkan kepedulian kita sebagai sesama anak bangsa.",
                'target_amount' => 1000000000,
                'collected_amount' => 320000000,
                'donor_count' => 1543,
                'image' => null,
                'start_date' => date('Y-m-d', strtotime('-5 days')),
                'end_date' => date('Y-m-d', strtotime('+25 days')),
                'status' => 'active',
                'is_featured' => 1,
                'is_urgent' => 1,
                'organizer_name' => 'PMI Sumatra',
                'organizer_phone' => '081234567892',
                'organizer_email' => 'pmi.sumatra@example.com',
                'views' => 12543,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 4, // Kemanusiaan
                'title' => 'Renovasi Panti Asuhan Cahaya Kasih',
                'slug' => 'renovasi-panti-asuhan-cahaya-kasih-' . time(),
                'short_description' => 'Panti asuhan dengan 50 anak membutuhkan renovasi mendesak',
                'description' => "Panti Asuhan Cahaya Kasih menampung 50 anak yatim piatu. Bangunan panti yang sudah berusia 40 tahun kini dalam kondisi memprihatinkan dengan atap bocor, tembok retak, dan kamar mandi rusak.\n\nRencana renovasi meliputi:\n- Perbaikan atap dan plafon\n- Pengecatan ulang\n- Renovasi kamar mandi\n- Perbaikan sistem listrik\n- Pembuatan ruang belajar yang layak\n\nDengan kondisi bangunan yang baik, anak-anak dapat tumbuh dan belajar dengan lebih nyaman dan aman.",
                'target_amount' => 150000000,
                'collected_amount' => 67000000,
                'donor_count' => 342,
                'image' => null,
                'start_date' => date('Y-m-d', strtotime('-15 days')),
                'end_date' => date('Y-m-d', strtotime('+45 days')),
                'status' => 'active',
                'is_featured' => 0,
                'is_urgent' => 0,
                'organizer_name' => 'Panti Asuhan Cahaya Kasih',
                'organizer_phone' => '081234567893',
                'organizer_email' => 'cahayakasih@example.com',
                'views' => 3421,
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 5, // Lingkungan
                'title' => 'Program Penanaman 10.000 Pohon',
                'slug' => 'program-penanaman-10000-pohon-' . time(),
                'short_description' => 'Mari bersama menanam 10.000 pohon untuk masa depan yang lebih hijau',
                'description' => "Indonesia kehilangan jutaan hektar hutan setiap tahunnya. Melalui program ini, kami akan menanam 10.000 pohon di berbagai wilayah yang mengalami deforestasi.\n\nProgram ini mencakup:\n- Pembelian bibit pohon berkualitas\n- Penanaman dengan melibatkan masyarakat lokal\n- Perawatan pohon selama 2 tahun pertama\n- Monitoring pertumbuhan pohon\n\nSetiap Rp 50.000 dapat menanam 1 pohon. Mari kita ciptakan masa depan yang lebih hijau untuk generasi mendatang!",
                'target_amount' => 500000000,
                'collected_amount' => 125000000,
                'donor_count' => 2500,
                'image' => null,
                'start_date' => date('Y-m-d', strtotime('-10 days')),
                'end_date' => date('Y-m-d', strtotime('+80 days')),
                'status' => 'active',
                'is_featured' => 1,
                'is_urgent' => 0,
                'organizer_name' => 'Yayasan Hijau Indonesia',
                'organizer_phone' => '081234567894',
                'organizer_email' => 'hijau.indonesia@example.com',
                'views' => 6789,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('campaigns')->insertBatch($data);
    }
}
