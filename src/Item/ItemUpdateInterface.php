<?php

namespace Foamycastle\Collection\Item;

interface ItemUpdateInterface
{
    function index(int $index): ItemUpdateInterface;
    function name(string $name): ItemUpdateInterface;
    function number(int $number):ItemUpdateInterface;
    function data(mixed $data): ItemUpdateInterface;
    function flag(int $flag, bool $state): ItemUpdateInterface;
}