<?php

namespace Foamycastle\Collection\ItemException;

use Foamycastle\Collection\ItemException;
use Foamycastle\Collection\ItemInterface;

class ImmutableItem extends ItemException
{
    public function __construct(?ItemInterface $item = null)
    {
        parent::__construct("This item (".$item?->getName().") is immutable.  Its data property can not be updated", $item);
    }

}