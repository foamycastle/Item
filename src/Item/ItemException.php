<?php

namespace Foamycastle\Collection\Item;

class ItemException extends \RuntimeException
{
    public function __construct(string $message,protected ?Item $item=null)
    {
        parent::__construct($message, 0x0f);
    }

}