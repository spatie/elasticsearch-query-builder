<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Sorts;

use PHPUnit\Framework\TestCase;
use App\Elasticsearch\ScriptSort;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sorting;

class ScriptSortTest extends TestCase
{
    private ScriptSort $scriptSort;

    protected function setUp(): void
    {
        $this->scriptSort = new ScriptSort("doc['field'].value", Sorting::ASC);
    }

    public function testImplementsSortingInterface(): void
    {
        $this->assertInstanceOf(Sorting::class, $this->scriptSort);
    }

    public function testToArrayBuildsCorrectSort(): void
    {
        $expected = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'source' => "doc['field'].value",
                    'lang' => 'painless',
                ],
                'order' => Sorting::ASC,
            ],
        ];

        $this->assertEquals($expected, $this->scriptSort->toArray());
    }

    public function testToArrayIncludesParams(): void
    {
        $scriptSource = "params.threshold > 10 ? 1 : 0";
        $params = ['threshold' => 15];

        $scriptSort = ScriptSort::create($scriptSource, Sorting::DESC)
            ->setParams($params);

        $expected = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'source' => $scriptSource,
                    'lang' => 'painless',
                    'params' => $params,
                ],
                'order' => Sorting::DESC,
            ],
        ];

        $this->assertEquals($expected, $scriptSort->toArray());
    }

    public function testCanSetLang(): void
    {
        $scriptSort = ScriptSort::create("return 1;", Sorting::ASC)
            ->setLang('expression');

        $expected = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'source' => "return 1;",
                    'lang' => 'expression',
                ],
                'order' => Sorting::ASC,
            ],
        ];

        $this->assertEquals($expected, $scriptSort->toArray());
    }

    public function testCanSetType(): void
    {
        $scriptSort = ScriptSort::create("doc['field'].value", Sorting::ASC)
            ->setType('string');

        $expected = [
            '_script' => [
                'type' => 'string',
                'script' => [
                    'source' => "doc['field'].value",
                    'lang' => 'painless',
                ],
                'order' => Sorting::ASC,
            ],
        ];

        $this->assertEquals($expected, $scriptSort->toArray());
    }

    public function testCanSetOrder(): void
    {
        $scriptSort = ScriptSort::create("doc['field'].value", Sorting::ASC)
            ->setOrder(Sorting::DESC);

        $expected = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'source' => "doc['field'].value",
                    'lang' => 'painless',
                ],
                'order' => Sorting::DESC,
            ],
        ];

        $this->assertEquals($expected, $scriptSort->toArray());
    }
}
