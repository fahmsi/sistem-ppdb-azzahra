<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key'   => 'hero_badge_text',
                'value' => 'Pendaftaran Dibuka 2026/2027',
                'label' => 'Teks Badge Hero',
                'group' => 'hero',
                'type'  => 'text',
            ],
            [
                'key'   => 'hero_title',
                'value' => 'Wujudkan Masa Depan Cemerlang Sejak Usia Dini',
                'label' => 'Judul Hero',
                'group' => 'hero',
                'type'  => 'text',
            ],

            // Agenda Section
            [
                'key'   => 'agenda_pembukaan_title',
                'value' => 'Pembukaan Pendaftaran',
                'label' => 'Judul Pembukaan (Agenda)',
                'group' => 'agenda',
                'type'  => 'text',
            ],
            [
                'key'   => 'agenda_pembukaan_date',
                'value' => '1 Mei — 30 Juni 2026',
                'label' => 'Tanggal Pembukaan (Agenda)',
                'group' => 'agenda',
                'type'  => 'text',
            ],
            [
                'key'   => 'agenda_pembukaan_desc',
                'value' => 'Pendaftaran dibuka secara online untuk calon peserta didik baru tahun ajaran 2026/2027.',
                'label' => 'Deskripsi Pembukaan (Agenda)',
                'group' => 'agenda',
                'type'  => 'textarea',
            ],
            [
                'key'   => 'agenda_pembukaan_status',
                'value' => 'active',
                'label' => 'Status Pembukaan (active/upcoming)',
                'group' => 'agenda',
                'type'  => 'text',
            ],

            // Footer Social Media
            [
                'key'   => 'social_facebook',
                'value' => '#',
                'label' => 'Facebook URL',
                'group' => 'footer',
                'type'  => 'text',
            ],
            [
                'key'   => 'social_instagram',
                'value' => '#',
                'label' => 'Instagram URL',
                'group' => 'footer',
                'type'  => 'text',
            ],
            [
                'key'   => 'social_youtube',
                'value' => '#',
                'label' => 'YouTube URL',
                'group' => 'footer',
                'type'  => 'text',
            ],
            [
                'key'   => 'social_tiktok',
                'value' => '#',
                'label' => 'TikTok URL',
                'group' => 'footer',
                'type'  => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
