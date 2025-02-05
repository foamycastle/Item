<?php

namespace Foamycastle\Collection;

class ItemUpdater extends Item implements ItemUpdateInterface
{
    private readonly Item $item;
    protected function __construct(Item $item)
    {
        $this->item = $item;
    }
    function index(int $index): ItemUpdateInterface
    {
        $this->item->setIndex($index);
        return $this;
    }

    function name(int|string $name): ItemUpdateInterface
    {
        $this->item->setName($name);
        return $this;
    }

    function number(int $number): ItemUpdateInterface
    {
        $this->item->setNumber($number);
        return $this;
    }

    function data(mixed $data): ItemUpdateInterface
    {
        $this->item->setData($data);
        return $this;
    }

    function flag(int $flag, bool $state): ItemUpdateInterface
    {
        $this->item->setFlags($flag,$state);
        return $this;
    }

}