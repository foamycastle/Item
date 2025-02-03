<?php

namespace Foamycastle\Collection;

use ArrayIterator;
use Foamycastle\Collection\Item\ItemInterface;

class Collection extends AbstractCollection implements CollectionInterface
{
    private function __construct()
    {
        parent::__construct();
    }

    public function offsetExists(mixed $offset): bool
    {
        if(is_int($offset)) {
            return $this->peek($offset);
        }
        return false;
    }

    public function offsetGet(mixed $offset): ?ItemInterface
    {
        if(is_int($offset)) {
            $this->seek($offset);
            return $this->current();
        }
        if(is_string($offset)) {
            $item = $this->findFirstItemByKey($offset);
            if($item!==-1){
                $this->seek($item);
                return $this->current();
            }
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if(is_int($offset)) {
            $this->seek($offset);
            if(!is_array($value)) {
                $key=$offset;
            }else{
                [$key,$value]=array_dissect($value);
            }
            if($this->valid()){
                $this->updateItemAtPointer()
                    ->key($key)
                    ->data($value);
            }else{
                $this->createItemAtPointer()
                    ->key($key)
                    ->data($value);
            }
            return;
        }
        if(is_null($offset)) {
            $this->findFirstEmptyIndex();
            if(!is_array($value)) {
                $key=$this->key();
            }else{
                [$key,$value]=array_dissect($value);
            }
            $this->createItemAtPointer()
                ->key($key)
                ->data($value);
            return;
        }
        $key=$offset;
        $index=$this->findFirstItemByKey($key);
        if(-1==$index){
            $this->findFirstEmptyIndex();
            $this->createItemAtPointer()
                ->key($key)
                ->data($value)
                ->index($this->key());
        }else{
            $this->seek($index);
            $this->updateItemAtPointer()
                ->key($key)
                ->data($value);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if(!is_int($offset)) return;
        if(!$this->peek($offset)) return;
        $this->clearItemAtIndex($offset);
        $this->clean();
    }

    public static function FromArray(array $data):Collection
    {
        $newCollection = new self();
        foreach($data as $key=>$value){
            $newCollection[$key] = $value;
        }
        return $newCollection;
    }

    public static function FromObject(object $data):Collection
    {
        $iterator = new ArrayIterator($data);

    }

}