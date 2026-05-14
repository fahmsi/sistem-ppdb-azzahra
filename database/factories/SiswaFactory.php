<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => fake()->name(),
            'nisn' => fake()->optional()->numerify('##########'),
            'nis' => fake()->optional()->numerify('########'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-7 years', '-4 years')->format('Y-m-d'),
            'agama' => 'Islam',
            'anak_ke' => fake()->numberBetween(1, 5),
            'jumlah_saudara' => fake()->numberBetween(0, 4),
            'hobi' => fake()->optional()->randomElement(['Menggambar', 'Bermain', 'Membaca', 'Bernyanyi']),
            'cita_cita' => fake()->optional()->randomElement(['Dokter', 'Guru', 'Polisi', 'Pilot']),
            'no_telpon' => fake()->numerify('08##########'),
            'jenis_tempat_tinggal' => fake()->randomElement(['Rumah Sendiri', 'Kontrak', 'Kost']),
            'alamat' => fake()->streetAddress(),
            'kelurahan' => fake()->citySuffix(),
            'kecamatan' => fake()->citySuffix(),
            'kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'kode_pos' => fake()->numerify('#####'),
            'transportasi' => fake()->optional()->randomElement(['Jalan Kaki', 'Motor', 'Mobil', 'Antar Jemput']),
            'no_kk' => fake()->numerify('################'),
            'kepala_keluarga' => fake()->name('male'),
            'nama_ayah' => fake()->name('male'),
            'nik_ayah' => fake()->numerify('################'),
            'tanggal_lahir_ayah' => fake()->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
            'pendidikan_ayah' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'pekerjaan_ayah' => fake()->randomElement(['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Buruh']),
            'penghasilan_ayah' => fake()->randomElement(['< 1 Juta', '1-3 Juta', '3-5 Juta', '> 5 Juta']),
            'nama_ibu' => fake()->name('female'),
            'nik_ibu' => fake()->numerify('################'),
            'tanggal_lahir_ibu' => fake()->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
            'pendidikan_ibu' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'pekerjaan_ibu' => fake()->randomElement(['Ibu Rumah Tangga', 'PNS', 'Wiraswasta', 'Karyawan Swasta']),
            'penghasilan_ibu' => fake()->randomElement(['< 1 Juta', '1-3 Juta', '3-5 Juta', '> 5 Juta']),
            'foto' => 'siswa/foto/placeholder.jpg',
            'foto_kk' => 'siswa/kk/placeholder.jpg',
            'foto_akta' => 'siswa/akta/placeholder.jpg',
        ];
    }
}
