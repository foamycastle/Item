<?php

namespace Foamycastle\Collection\Item;

interface ItemInterface extends \Stringable
{
    function isEmpty():bool;
    function isOrphan():bool;
    function isObject():bool;
    function getType():string;
    function getObjectClass():string;
    function getObjectId():string;
    function getParent():?object;
    function getData():mixed;
    function getKey():string|int|null;
    function getIndex():?int;
    function setKey(int|string $key):self;
    function setIndex(int $index):self;
    function setData(mixed $data):self;
    function setParent(object $parent):self;


}