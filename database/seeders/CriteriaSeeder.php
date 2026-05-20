<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // SQA Guard: Kosongkan/bersihkan tabel kriteria lama agar tidak duplikat
        Criteria::truncate();

        // Susunan 8 Kriteria Final beserta Bobot & Parameter Fungsi Preferensi
        $kriteriaFinal = [
            ['name' => 'Harga (Diamond)', 'type' => 'minimize', 'weight' => 1.5, 'preference_function' => 'linear', 'p' => 9000, 'q' => 0, 's' => 0],
            ['name' => 'Kategori Skin', 'type' => 'maximize', 'weight' => 1.2, 'preference_function' => 'level', 'p' => 4, 'q' => 1, 's' => 0],
            ['name' => 'Model Skin', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'linear_quasi', 'p' => 7, 'q' => 1, 's' => 0],
            ['name' => 'Portrait Skin', 'type' => 'maximize', 'weight' => 0.8, 'preference_function' => 'linear_quasi', 'p' => 7, 'q' => 1, 's' => 0],
            ['name' => 'Animasi Entrance', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'linear_quasi', 'p' => 7, 'q' => 1, 's' => 0],
            ['name' => 'In-Game Effect', 'type' => 'maximize', 'weight' => 1.4, 'preference_function' => 'linear_quasi', 'p' => 7, 'q' => 1, 's' => 0],
            ['name' => 'Tingkat Preferensi Hero', 'type' => 'maximize', 'weight' => 1.1, 'preference_function' => 'quasi', 'p' => 0, 'q' => 2, 's' => 0],
            ['name' => 'Status Ketersediaan Skin', 'type' => 'maximize', 'weight' => 0.7, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
        ];

        // Masukkan data ke database lewat Model
        foreach ($kriteriaFinal as $kriteria) {
            Criteria::create($kriteria);
        }
    }
}
