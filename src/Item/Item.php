<?php

namespace Foamycastle\Collection\Item;

use Foamycastle\Collection\Item\ItemException\ImmutableItem;

abstract class Item

{
    public const IMMUTABLE = 512;
    public const THROWABLE = 1024;
    private string $id;
    private string $type;
    private int $flags;
    private function __construct(
        private int $index,
        private null|int|string $name=null,
        private mixed $data=null,
        int $flags=0
    )
    {
        $this->id = spl_object_id($this);
        $this->flags = $flags;
        $this->type = gettype($data);
    }
    protected function setIndex(int $index): void
    {
        $this->index = $index;
    }
    protected function setName(string $name): void
    {
        $this->name = $name;
    }
    protected function setNumber(int $number): void
    {
        $this->name = $number;
    }
    protected function setData(mixed $data): void
    {
        if($this->isImmutable()) {
            if($this->doesThrow()){
                throw new ImmutableItem($this);
            }
        }
        $this->data = $data;
        $this->type = gettype($data);
    }

    /**
     * @param int<value-of<self::*>> $flag
     * @param bool $state
     * @return void
     */
    protected function setFlags(int $flag, ?bool $state=null): void
    {
        if(is_null($state)) {
            $this->flags = $flag;
        }else {
            if ($state) {
                $this->flags |= $flag;
            } else {
                $this->flags &= ~$flag;
            }
        }
    }

    function isImmutable(): bool
    {
        return ($this->flags ?? 0) & self::IMMUTABLE;
    }

    function doesThrow(): bool
    {
        return ($this->flags ?? 0) & self::THROWABLE;
    }

    function hasNumber(): bool
    {
        return is_int($this->name);
    }

    function getName(): string|int
    {
        return $this->name ?? '';
    }

    public function getData(): mixed
    {
        return $this->data ?? null;
    }
    public function getIndex(): int
    {
        return $this->index ?? -1;
    }
    public function getType(): string
    {
        return $this->type ?? '';
    }

    public function getId(): string
    {
        return $this->id ?? '';
    }
    function update():ItemUpdateInterface
    {
        return new ItemUpdater($this);
    }

}