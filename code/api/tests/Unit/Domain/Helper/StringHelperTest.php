<?php

namespace Tests\Unit\Domain\Helper;

use App\Domain\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    /**
     * @dataProvider diacriticsProvider
     *
     * @param string $originalString
     * @param string $expectedString
     */
    public function testRemoveDiacritics(string $originalString, string $expectedString): void
    {
        $stringHelper = new StringHelper();
        $this->assertSame($expectedString, $stringHelper->removeDiacritics($originalString));
    }

    public function diacriticsProvider(): array
    {
        return [
            [
                'originalString' => 'abcd',
                'expectedString' => 'abcd',
            ],
            [
                'originalString' => 'èe',
                'expectedString' => 'ee',
            ],
            [
                'originalString' => '€',
                'expectedString' => '€',
            ],
            [
                'originalString' => 'àòùìéëü',
                'expectedString' => 'aouieeu',
            ],
            [
                'originalString' => 'àòùìéëu',
                'expectedString' => 'aouieeu',
            ],
            [
                'originalString' => 'ÁOÒòoÍìI',
                'expectedString' => 'AOOooIiI',
            ],
            [
                'originalString' => 'tiësto',
                'expectedString' => 'tiesto',
            ],
        ];
    }

    /**
     * @dataProvider wordPermutationsProvider
     *
     * @param string $originalString
     * @param array $expectedPermutations
     */
    public function testGetSanitizedWordPermutations(string $originalString, array $expectedPermutations): void
    {
        $stringHelper = new StringHelper();
        $this->assertSame($expectedPermutations, $stringHelper->getSanitizedWordPermutations($originalString));
    }

    public function wordPermutationsProvider(): array
    {
        return [
            [
                'originalString' => 'La vita è bella',
                'expectedPermutations' => [
                    'la',
                    'la vita',
                    'la vita e',
                    'la vita e bella',
                    'vita',
                    'vita e',
                    'vita e bella',
                    'e',
                    'e bella',
                    'bella',
                ],
            ]
        ];
    }
}
