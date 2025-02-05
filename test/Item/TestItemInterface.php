<?php

namespace Foamycastle\Collection\Test\Item;

use Foamycastle\Collection\Item\Item;
use Foamycastle\Collection\Item\ItemCreatorInterface;
use Foamycastle\Collection\Item\ItemInterface;

class TestItemInterface extends Item implements ItemInterface, ItemCreatorInterface
{
    private function __construct()
    {
    }

    public static function Create(int $index, string $name, mixed $data): static
    {
        $newSelf=new self();
        $newSelf->setIndex($index);
        $newSelf->setName($name);
        $newSelf->setData($data);
        return $newSelf;
    }

    public static function CreateImmutable(int $index, string $name, mixed $data): static
    {
        $newSelf=self::Create($index, $name, $data);
        $newSelf->setFlags(Item::IMMUTABLE | Item::THROWABLE);
        return $newSelf;
    }

    public static function Anon(int $index): static
    {
        $newSelf = new self();
        $newSelf->setIndex($index);
        return $newSelf;
    }

}