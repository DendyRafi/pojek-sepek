<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class DefaultCriteria
{
    /**
     * @return array<int, array{name: string, type: string, weight: float, preference_function: string, p: float, q: float, s: float}>
     */
    public static function records(): array
    {
        return [
            ['name' => 'Harga (Diamond)', 'type' => 'minimize', 'weight' => 1.5, 'preference_function' => 'linear', 'p' => 9000, 'q' => 0, 's' => 0],
            ['name' => 'Kategori Skin', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'Model Skin', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'Portrait Skin', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'Animasi Entrance', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'In-Game Effect', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'Tingkat Preferensi Hero', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
            ['name' => 'Status Ketersediaan Skin', 'type' => 'maximize', 'weight' => 1.0, 'preference_function' => 'usual', 'p' => 0, 'q' => 0, 's' => 0],
        ];
    }

    /**
     * @return array<int, array{name: string, type: string, weight: float, preference_function: string, p: float, q: float, s: float, created_at: Carbon, updated_at: Carbon}>
     */
    public static function recordsWithTimestamps(): array
    {
        $now = now();

        return array_map(
            fn (array $criteria): array => [
                ...$criteria,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            self::records(),
        );
    }
}
