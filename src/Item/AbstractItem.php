<?php

namespace Foamycastle\Collection\Item;

abstract class AbstractItem implements ItemInterface
{

    /**
     * A callable that can be set to determine if an item is considered empty
     * @var callable
     */
    protected static $emptyLogic;
    private string|int $key;
    private mixed $data;
    private mixed $parent;
    private int $index;

    public function setKey(string|int $key): ItemInterface
    {
        $this->key = $key;
        return $this;
    }
    public function setData(mixed $data): ItemInterface
    {
        $this->data = $data;
        return $this;
    }
    public function setParent(mixed $parent): ItemInterface
    {
        $this->parent = $parent;
        return $this;
    }
    public function setIndex(int $index): ItemInterface
    {
        $this->index = $index;
        return $this;
    }

    public function getKey(): int|string|null
    {
        return ($this->key ?? null);
    }

    public function getData(): mixed
    {
        return ($this->data ?? null);
    }

    public function getParent(): ?object
    {
        return ($this->parent ?? null);
    }

    public function getIndex(): ?int
    {
        return ($this->index ?? null);
    }

    function isEmpty(): bool
    {
        return isset(self::$emptyLogic)
            ? self::$emptyLogic($this)
            : $this->data === null;
    }

    function isOrphan(): bool
    {
        return $this->parent !== null;
    }

    function isObject(): bool
    {
        return $this->getType()=='object';
    }

    function getType(): string
    {
        return gettype($this->data);
    }

    function getObjectClass(): string
    {
        return $this->isObject()
            ? get_class($this->data)
            : '';
    }

    function getObjectId(): string
    {
        return $this->isObject()
            ? spl_object_id($this->data)
            : '';
    }
    public function __toString(): string
    {
        if($this->data instanceof \Serializable) {
            return $this->data->serialize() ?? '';
        }
        if($this->data instanceof \Stringable){
            return $this->data->__toString();
        }
        if(is_scalar($this->data)) {
            if(is_bool($this->data)){
                return $this->data?'true':'false';
            }
            return (string)$this->data;
        }
        return print_r($this->data, true);
    }

}