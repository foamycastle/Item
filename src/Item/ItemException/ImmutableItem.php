<?php

namespace Foamycastle\Collection\Item\ItemException;

use Foamycastle\Collection\Item\Item;
use Foamycastle\Collection\Item\ItemException;
use Foamycastle\Collection\Item\ItemInterface;

class ImmutableItem extends ItemException
{
    public function __construct(?ItemInterface $item = null)
    {
        parent::__construct("This item (".$item?->getName().") is immutable.  Its data property can not be updated", $item);
    }

}