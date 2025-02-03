<?php

namespace Foamycastle\Collection;

use Foamycastle\Collection\Attribute\FilterMethod;
use Foamycastle\Collection\Attribute\WalkMethod;
use Foamycastle\Collection\Item\Item;
use Foamycastle\Collection\Item\ItemInterface;
use Foamycastle\Collection\Item\ItemUpdateInterface;

abstract class AbstractCollection implements \Iterator,\Countable
{

    /**
     * @var ItemInterface[]
     */
    private array $_items;
    private int $pointer=0;
    private int $count=0;

    protected function __construct()
    {
        $this->init();
    }
    public function current(): ?ItemInterface
    {
        if($this->count==0) return null;
        if(!$this->valid()) return null;
        return $this->_items[$this->pointer];
    }

    public function next(): void
    {
        $this->incPointer();
    }

    public function key(): int|string
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return isset($this->_items[$this->pointer]);
    }

    public function rewind(): void
    {
        reset($this->_items);
        $this->pointer = 0;
    }

    public function count(): int
    {
        return $this->count;
    }
    final protected function init()
    {
        $this->_items = [];
        $this->resetCount();
        $this->resetPointer();
    }
    final protected function incCount(): int
    {
        return ++$this->count;
    }
    final protected function decCount(): int
    {
        if($this->count==0) return 0;
        return --$this->count;
    }
    final protected function incPointer(): int
    {
        return ++$this->pointer;
    }
    final protected function decPointer(): int
    {
        if($this->pointer==0) return 0;
        return --$this->pointer;
    }
    final protected function resetCount(): int
    {
        $this->count = 0;
        return $this->count;
    }

    final protected function resetPointer():int
    {
        $this->pointer = 0;
        return $this->pointer;
    }

    /**
     * Returns the index of the final element in the items array
     * @return int
     */
    final protected function lastIndex():int
    {
        if($this->count==0) return 0;
        return max([...array_keys($this->_items)]);
    }

    /**
     * Indicates the item pointer is pointing to the last item in the array
     * @return bool
     */
    final protected function end():bool
    {
        return $this->pointer==$this->lastIndex();
    }

    /**
     * Place the pointer at the specified position
     * @param int $position
     * @return void
     */
    final protected function seek(int $position):void
    {
        $this->pointer = $position;
    }

    final protected function seekToNearest():void
    {
        $centerMinus=$centerPlus=$this->pointer;
        $last=$this->lastIndex();
        while(true){
            $centerMinus--;
            $centerPlus++;
            if($this->peek($centerMinus)){
                $this->seek($centerMinus);
                return;
            }
            if($this->peek($centerPlus)){
                $this->seek($centerPlus);
                return;
            }
            if($centerMinus<0){
                $this->seek(0);
                return;
            }
            if($centerPlus>$last){
                $this->seek($last);
                return;
            }
        }
    }

    /**
     * Indicate if the specified position is occupied
     * @param int $position
     * @return bool
     */
    final protected function peek(int $position):bool{
        if($position<0 || $position>$this->lastIndex()) return false;
        return isset($this->_items[$position]);
    }

    /**
     * Swap the positions of 2 items based on their index in the items array
     * @param int $pos1 The numeric index of item 1
     * @param int $pos2 The numeric index of item 2
     * @return void
     */
    final protected function swap(int $pos1, int $pos2):void
    {
        if($pos1==$pos2) return;
        if($pos1<0 || $pos1>$this->lastIndex()) return;
        if($pos2<0 || $pos2>$this->lastIndex()) return;

        $item1=$this->_items[$pos1];
        $item1Index=$item1->getIndex();
        $item2=$this->_items[$pos2];
        $item2Index=$item2->getIndex();
        $this->_items[$pos1]=$item2;
        Item::Update($item2)->index($pos1);

        $this->_items[$pos2]=$item1;
        Item::Update($item1)->index($pos2);
    }

    /**
     * Removes items with null data and re-indexes the entire item array
     * @return void
     */
    final protected function clean():void
    {
        $this->resetPointer();
        $this->_items=$this->filter([$this,'deleteEmptyData']);
        $this->walk([$this,'updateIndex']);
    }

    /**
     * Apply a filter to the items array
     * @param callable $logicFunction the callable that determines which items are filtered out of the array
     * @return array the filtered array
     */
    final protected function filter(callable $logicFunction):array
    {
        return array_filter($this->_items,$logicFunction);
    }

    /**
     * Apply a function to all elements of the array
     * @param callable $walkFunction the function or method that is applied to all items in the array
     * @return void
     */
    final protected function walk(callable $walkFunction):void
    {
        array_walk($this->_items,$walkFunction);
    }

    /**
     * A filter method that indicates if the data property of an item is empty
     * @param ItemInterface|null $item
     * @return bool
     */
    final protected function deleteEmptyData(?ItemInterface $item):bool
    {
        return null!==$item || null!==$item->getData();
    }

    /**
     * A filter method that indicates if either the key or data property of an item is empty
     * @param ItemInterface|null $item
     * @return bool
     */
    final protected function deleteEmptyKeyAndData(?ItemInterface $item):bool
    {
        return null===$item || (null===$item->getData() && null===$item->getKey());
    }

    /**
     * A walk method that update the item's index property, forcing it to match its position in the item array.
     * @param ItemInterface $item
     * @param int $key
     * @return void
     */
    final protected function updateIndex(ItemInterface $item, int $key):void
    {
        Item::Update($item)->index($key);
    }

    /**
     * Locates the first position in the items array that contains an empty item
     * @return void
     */
    final protected function findFirstEmptyIndex():void
    {
        $this->resetPointer();
        if($this->count()==0) return;
        while($this->valid()){
            $this->incPointer();
        }
    }

    /**
     * Locates the next position, relative to the current pointer position, that does not contain an item object
     * @return void
     */
    final protected function findNextEmptyIndex():void
    {
        while($this->valid()){
            $this->incPointer();
        }
        $this->pointer++;
    }

    /**
     * Place the specified item reference at the current pointer position
     * @return ItemUpdateInterface|null Returns the item interface
     */
    final protected function updateItemAtPointer():?ItemUpdateInterface
    {
        return Item::Update($this->current());
    }

    /**
     * Place the pointer at a specific index and place an item at that index
     * @param int $index the index at the
     * @return ItemUpdateInterface|null Returns the placed item
     */
    final protected function updateItemAtIndex(int $index):?ItemUpdateInterface
    {
        $this->seek($index);
        return $this->updateItemAtPointer();
    }

    /**
     * Create an item at the current pointer
     * @return ItemUpdateInterface
     */
    final protected function createItemAtPointer():ItemUpdateInterface
    {
        if(!$this->valid()){
            $this->incCount();
        }
        $newItem= $this->_items[$this->pointer] = Item::Create($this,$this->pointer);
        return Item::Update($newItem);
    }

    /**
     * Create an item at a specific index
     * @param int $index
     * @return ItemUpdateInterface
     */
    final protected function createItemAtIndex(int $index):ItemUpdateInterface
    {
        $this->seek($index);
        return $this->createItemAtPointer();
    }

    /**
     * Eliminate the item and the current pointer
     * @return void
     */
    final protected function clearItemAtPointer():void
    {
        if(!$this->valid()) return;
        $this->decCount();
        unset($this->_items[$this->pointer]);
    }

    /**
     * Eliminate the item at the given index
     * @param int $index
     * @return void
     */
    final protected function clearItemAtIndex(int $index):void
    {
        if(!isset($this->_items[$index])) return;
        $this->decCount();
        unset($this->_items[$index]);
    }

    /**
     * Return the index of the first collection item with the specified key
     * @param string|int $key specified key
     * @param int $index begin the search at this index
     * @return int the index position in the collection
     */
    final protected function findFirstItemByKey(string|int $key, int $index=0):int
    {
        $results=array_filter(
            array_slice($this->_items,$index),
            function (Item $item) use ($key) {
                return $item->getKey()===$key;
            }
        );
        if(count($results)==0) return -1;
        return reset($results)->getIndex();
    }
}