<?php

namespace Foamycastle\Collection\Item;

final class ItemUpdater implements ItemUpdateInterface
{
    public function __construct(private readonly Item $item)
    {
    }

    function key(int|string $key): self
    {
        $this->item->setKey($key);
        return $this;
    }

    function data(mixed $data): self
    {
        $this->item->setData($data);
        return $this;
    }

    function index(int $index): self
    {
        $this->item->setIndex($index);
        return $this;
    }

}