<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

use Spatie\ElasticsearchQueryBuilder\Sorts\Sorting;

class ScriptSort implements Sorting
{
    protected string $scriptSource;
    protected string $order;
    protected string $lang = 'painless';
    protected string $type = 'number';
    protected array $params = [];

    public function __construct(
        string $scriptSource,
        string $order = self::ASC,
        string $lang = 'painless',
        string $type = 'number',
        array $params = []
    ) {
        $this->scriptSource = $scriptSource;
        $this->order = $order;
        $this->lang = $lang;
        $this->type = $type;
        $this->params = $params;
    }

    public static function create(
        string $scriptSource,
        string $order = self::ASC,
        string $lang = 'painless',
        string $type = 'number',
        array $params = []
    ): static {
        return new static($scriptSource, $order, $lang, $type, $params);
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function toArray(): array
    {
        $scriptArray = [
            'source' => $this->scriptSource,
            'lang' => $this->lang,
        ];

        if (!empty($this->params)) {
            $scriptArray['params'] = $this->params;
        }

        return [
            '_script' => [
                'type' => $this->type,
                'script' => $scriptArray,
                'order' => $this->order,
            ],
        ];
    }
}
