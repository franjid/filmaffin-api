<?php

namespace Tests\Unit\Domain\Helper;

use App\Domain\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    private StringHelper $stringHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stringHelper = new StringHelper();
    }

    /**
     * @dataProvider diacriticsDataProvider
     */
    public function testRemoveDiacritics(string $originalString, string $expectedString): void
    {
        $this->assertSame($expectedString, $this->stringHelper->removeDiacritics($originalString));
    }

    public function diacriticsDataProvider(): array
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
     * @dataProvider wordPermutationsDataProvider
     */
    public function testGetSanitizedWordPermutations(string $originalString, array $expectedPermutations): void
    {
        $this->assertSame($expectedPermutations, $this->stringHelper->getSanitizedWordPermutations($originalString));
    }

    public function wordPermutationsDataProvider(): array
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
