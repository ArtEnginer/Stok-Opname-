<?php

if (!function_exists('campaign_image_url')) {
    /**
     * Get campaign image URL
     * 
     * @param string|null $filename
     * @return string
     */
    function campaign_image_url(?string $filename): string
    {
        if (empty($filename)) {
            return 'https://via.placeholder.com/800x450/22c55e/ffffff?text=DonasiKita';
        }

        // Check if file exists
        $path = WRITEPATH . 'uploads/campaigns/' . $filename;
        if (file_exists($path)) {
            return base_url('writable/uploads/campaigns/' . $filename);
        }

        return 'https://via.placeholder.com/800x450/22c55e/ffffff?text=DonasiKita';
    }
}

if (!function_exists('payment_proof_url')) {
    /**
     * Get payment proof image URL
     * 
     * @param string|null $filename
     * @return string
     */
    function payment_proof_url(?string $filename): string
    {
        if (empty($filename)) {
            return '';
        }

        $path = WRITEPATH . 'uploads/payment_proofs/' . $filename;
        if (file_exists($path)) {
            return base_url('writable/uploads/payment_proofs/' . $filename);
        }

        return '';
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format number to IDR currency
     * 
     * @param float $amount
     * @return string
     */
    function format_currency(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_short_number')) {
    /**
     * Format large numbers to K, M, B
     * 
     * @param float $number
     * @return string
     */
    function format_short_number(float $number): string
    {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 1) . 'M';
        }
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'Jt';
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        return number_format($number, 0);
    }
}

if (!function_exists('time_ago')) {
    /**
     * Convert datetime to human readable format
     * 
     * @param string $datetime
     * @return string
     */
    function time_ago(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        }
        if ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        }
        if ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        }
        if ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        }

        return date('d M Y', $timestamp);
    }
}

if (!function_exists('generate_slug')) {
    /**
     * Generate URL-friendly slug
     * 
     * @param string $text
     * @return string
     */
    function generate_slug(string $text): string
    {
        return url_title($text, '-', true) . '-' . time();
    }
}

if (!function_exists('calculate_progress')) {
    /**
     * Calculate campaign progress percentage
     * 
     * @param float $collected
     * @param float $target
     * @return float
     */
    function calculate_progress(float $collected, float $target): float
    {
        if ($target <= 0) {
            return 0;
        }
        return min(($collected / $target) * 100, 100);
    }
}

if (!function_exists('days_left')) {
    /**
     * Calculate days left until end date
     * 
     * @param string $endDate
     * @return int
     */
    function days_left(string $endDate): int
    {
        $now = new DateTime();
        $end = new DateTime($endDate);
        $diff = $now->diff($end);

        if ($diff->invert) {
            return 0;
        }

        return $diff->days;
    }
}

if (!function_exists('status_badge')) {
    /**
     * Get HTML badge for status
     * 
     * @param string $status
     * @return string
     */
    function status_badge(string $status): string
    {
        $badges = [
            'active' => '<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Aktif</span>',
            'draft' => '<span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">Draft</span>',
            'completed' => '<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">Selesai</span>',
            'cancelled' => '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Dibatalkan</span>',
            'pending' => '<span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Pending</span>',
            'verified' => '<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Terverifikasi</span>',
            'rejected' => '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Ditolak</span>',
        ];

        return $badges[$status] ?? '<span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">' . ucfirst($status) . '</span>';
    }
}
