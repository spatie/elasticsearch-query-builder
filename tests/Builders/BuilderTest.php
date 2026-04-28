<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Builders;

use Elastic\Elasticsearch\Client;
use Elastic\Transport\TransportBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class BuilderTest extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $transport = TransportBuilder::create()
            ->setClient(new \Http\Mock\Client)
            ->build();

        $logger = $this->createStub(LoggerInterface::class);

        $this->client = new Client($transport, $logger);
    }

    public function test_generates_collapse_with_plain_array_data(): void
    {
        $innerHits = [
            'name' => 'first_group',
            'size' => 1,
            'sort' => [
                ['name.keyword' => ['order' => 'asc']],
            ],
        ];

        $builder = (new Builder($this->client))
            ->collapse('group_id', $innerHits);

        self::assertEquals(
            ['collapse' => ['field' => 'group_id', 'inner_hits' => $innerHits]],
            $builder->getPayload()
        );
    }

    public function test_generates_collapse_with_inner_hits_object(): void
    {
        $innerHits = InnerHits::create('first_group')
            ->size(1)
            ->addSort(new Sort('name.keyword', 'asc'));

        $builder = (new Builder($this->client))
            ->collapse('group_id', $innerHits);

        self::assertEquals(
            [
                'collapse' => [
                    'field' => 'group_id', 'inner_hits' => [
                        'name' => 'first_group',
                        'size' => 1,
                        'sort' => [
                            [
                                'name.keyword' => [
                                    'order' => 'asc',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $builder->getPayload()
        );
    }

    public function test_min_score_is_applied_to_the_payload(): void
    {
        $payload = (new Builder($this->client))
            ->minScore(0.1)
            ->getPayload();

        $this->assertArrayHasKey('min_score', $payload);
        $this->assertEquals(0.1, $payload['min_score']);
    }

    /**
     * This test is to document current behavior
     * and catch breaking changes if the "fields" method should be renamed at some point
     * (to better reflect the payload parameter).
     */
    public function test_treats_fields_as_source_fields(): void
    {
        $builder = (new Builder($this->client))
            ->fields(['includes' => ['my_included_source_field'], 'excludes' => ['my_excluded_source_field']]);

        $this->assertEquals(
            ['includes' => ['my_included_source_field'], 'excludes' => ['my_excluded_source_field']],
            $builder->getPayload()['_source']
        );
    }

    public function test_retrieve_fields(): void
    {
        $builder = (new Builder($this->client))
            ->source(false)
            // Switch back and forth between types to check compatibility.
            ->source(['my_source_field'])
            ->source(false)
            ->retrieveFields(['my_field']);

        $payload = $builder->getPayload();
        $this->assertEquals(['my_field'], $payload['fields']);
        $this->assertFalse($payload['_source']);
    }
}
