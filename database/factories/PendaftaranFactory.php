<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pendaftaran>
 */
class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('now', '+3 months');
        $selesai = (clone $mulai)->modify('+30 days');

        return [
            'tahun_ajaran' => '2026/2027',
            'gelombang' => 'Gelombang ' . fake()->randomElement(['1', '2', '3']),
            'kuota' => fake()->numberBetween(20, 50),
            'status' => fake()->randomElement(['buka', 'tutup']),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $selesai->format('Y-m-d'),
            'gambar' => null,
        ];
    }

    /**
     * Set the pendaftaran as open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'buka',
        ]);
    }

    /**
     * Set the pendaftaran as closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tutup',
        ]);
    }
}
