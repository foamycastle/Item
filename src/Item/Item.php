<?php

namespace Foamycastle\Collection\Item;

use Foamycastle\Collection\Item\AbstractItem;

class Item extends AbstractItem
{
    protected function __construct(
        ?object $parent,
        ?int $index = null,
        int|string|null $key = null,
        mixed $data = null,
    )
    {
        if($key) {
            $this->setKey($key);
        }
        if($data!==null) {
            $this->setData($data);
        }
        if($index) {
            $this->setIndex($index);
        }
        if($parent) {
            $this->setParent($parent);
        }
    }

    public static function Create(
        ?object $parent,
        ?int $index = null,
        int|string|null $key = null,
        mixed $data = null,
    ):ItemInterface
    {
        return new static($parent, $index, $key, $data);
    }

    public static function Update(Item $item):ItemUpdateInterface
    {
        return new ItemUpdater($item);
    }

    /**
     * Allows user to set the logic that determines if an item should be considered empty
     * @param callable $logic
     * @return callable|null Is the return logic has been previously set, the previous callable is returned, null otherwise.
     */
    public static function SetEmptyLogic(callable $logic):?callable
    {
        $returnThis=(self::$emptyLogic ?? null);
        self::$emptyLogic=$logic;
        return $returnThis;
    }
}