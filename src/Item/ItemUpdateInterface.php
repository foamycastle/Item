<?php

namespace Foamycastle\Collection\Item;

interface ItemUpdateInterface
{
    function key(int|string $key): ItemUpdateInterface;
    function data(mixed $data): ItemUpdateInterface;
    function index(int $index): ItemUpdateInterface;
}