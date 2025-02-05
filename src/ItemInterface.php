<?php

namespace Foamycastle\Collection;

interface ItemInterface
{
    function isImmutable():bool;
    function doesThrow():bool;
    function getName():string|int;
    function getData():mixed;
    function getIndex():int;
    function getType():string;
    function hasNumber():bool;
    function update():ItemUpdateInterface;
    function getId():string;

}