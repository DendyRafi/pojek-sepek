<?php

namespace Tests\Unit;

use App\Libraries\Promethee;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PrometheeLibraryTest extends TestCase
{
    #[DataProvider('preferenceCases')]
    public function test_it_matches_reference_preference_functions(float $deviation, array $criterion, float $expected): void
    {
        $promethee = new Promethee;

        $this->assertEqualsWithDelta($expected, $promethee->preference($deviation, $criterion), 0.001);
    }

    public function test_it_ranks_alternatives_by_promethee_net_flow(): void
    {
        $promethee = new Promethee;

        $results = $promethee->calculate([
            ['name' => 'Skin Mahal Biasa', 'scores' => [1 => 5000, 2 => 2, 3 => 3]],
            ['name' => 'Skin Murah Efek Bagus', 'scores' => [1 => 1000, 2 => 4, 3 => 6]],
            ['name' => 'Skin Menengah', 'scores' => [1 => 2500, 2 => 3, 3 => 4]],
        ], [
            ['id' => 1, 'name' => 'Harga', 'direction' => 'min', 'weight' => 1, 'preference_function' => Promethee::LINEAR, 'p' => 5000],
            ['id' => 2, 'name' => 'Rarity', 'direction' => 'max', 'weight' => 1, 'preference_function' => Promethee::USUAL],
            ['id' => 3, 'name' => 'Efek', 'direction' => 'max', 'weight' => 1, 'preference_function' => Promethee::LINEAR_QUASI, 'p' => 7, 'q' => 1],
        ]);

        $this->assertSame('Skin Murah Efek Bagus', $results[0]['name']);
        $this->assertSame(1, $results[0]['rank']);
        $this->assertGreaterThan($results[1]['net_flow'], $results[0]['net_flow']);
    }

    /**
     * @return array<string, array{0: float, 1: array<string, float|int|string>, 2: float}>
     */
    public static function preferenceCases(): array
    {
        return [
            'usual no difference' => [0, ['preference_function' => Promethee::USUAL], 0],
            'usual strict preference' => [2, ['preference_function' => Promethee::USUAL], 1],
            'linear partial' => [3.5, ['preference_function' => Promethee::LINEAR, 'p' => 7], 0.5],
            'linear full' => [8, ['preference_function' => Promethee::LINEAR, 'p' => 7], 1],
            'quasi indifference' => [2, ['preference_function' => Promethee::QUASI, 'q' => 2], 0],
            'quasi preference' => [3, ['preference_function' => Promethee::QUASI, 'q' => 2], 1],
            'linear quasi partial' => [2, ['preference_function' => Promethee::LINEAR_QUASI, 'p' => 7, 'q' => 1], 0.333333],
            'level weak preference' => [3, ['preference_function' => Promethee::LEVEL, 'p' => 7, 'q' => 1], 0.5],
            'level full preference' => [8, ['preference_function' => Promethee::LEVEL, 'p' => 7, 'q' => 1], 1],
            'gaussian preference' => [1, ['preference_function' => Promethee::GAUSSIAN, 's' => 1], 0.393469],
        ];
    }
}
