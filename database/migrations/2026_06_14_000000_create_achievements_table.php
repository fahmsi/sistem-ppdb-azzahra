<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('level', 100);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('achievement_year')->nullable();
            $table->string('image');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('achievements')->insert([
            [
                'title' => 'Juara Hafalan Quran',
                'level' => 'Kota Depok',
                'description' => 'Prestasi hafalan Al-Qur\'an yang membanggakan dari siswa Az-Zahra.',
                'achievement_year' => 2026,
                'image' => 'https://images.unsplash.com/photo-1667967699372-1c26d40dec46?w=900&auto=format&fit=crop',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Juara Mewarnai',
                'level' => 'Tingkat Kecamatan',
                'description' => 'Kreativitas dan keberanian anak berkembang melalui kegiatan seni.',
                'achievement_year' => 2026,
                'image' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=900&auto=format&fit=crop',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Juara Lomba Adzan',
                'level' => 'Kota Depok',
                'description' => 'Kepercayaan diri dan kecintaan beribadah ditanamkan sejak usia dini.',
                'achievement_year' => 2026,
                'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=900&auto=format&fit=crop',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
