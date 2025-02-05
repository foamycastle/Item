<?php

namespace Foamycastle\Collection\Test\Item;

use Foamycastle\Collection\Item\Item;
use Foamycastle\Collection\Item\ItemException\ImmutableItem;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;

class TestItemTest extends TestCase
{

    public function testIsImmutable()
    {
        $item=TestItem::CreateImmutable(0,'testItem',base64_encode(random_bytes(32)));
        $this->expectException(ImmutableItem::class);
        $item->update()->data(base64_encode(random_bytes(32)));
    }

    public function testGetName()
    {
        $item=TestItem::Create(0,'testItem',base64_encode(random_bytes(32)));
        $this->assertSame('testItem', $item->getName());
    }

    public function testUpdate()
    {
        $item=TestItem::Create(0,'testItem','test item data');
        $this->assertSame('test item data', $item->getData());
        $newData=base64_encode(random_bytes(32));
        $item->update()->data($newData);
        $this->assertSame($newData, $item->getData());
    }

    public function testGetType()
    {
        $item=TestItem::Create(0,'testItem',0x1123A);
        $this->assertIsInt($item->getData());
        $this->assertSame('integer', $item->getType());
    }

    public function testHasNumber()
    {
        $item=TestItem::Create(0,'testItem',0x1123A);
        $this->assertFalse($item->hasNumber());
    }

    public function testGetIndex()
    {
        $item=TestItem::Create(0x0662,'testItem','this is a test item data');
        $this->assertSame(0x0662, $item->getIndex());
    }

    public function testAnon()
    {
        $item=TestItem::Anon(45);
        $this->assertSame(45, $item->getIndex());
        $this->assertNull($item->getData());
        $this->assertNull($item->getName());
    }

    public function testDoesThrow()
    {

    }

    public function testGetData()
    {

    }

}
