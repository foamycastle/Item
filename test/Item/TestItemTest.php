<?php

namespace Foamycastle\Collection\Test\Item;

use Foamycastle\Collection\ItemException\ImmutableItem;
use PHPUnit\Framework\TestCase;

class TestItemTest extends TestCase
{

    public function testIsImmutable()
    {
        $item=TestItemInterface::CreateImmutable(0,'testItem',base64_encode(random_bytes(32)));
        $this->expectException(ImmutableItem::class);
        $item->update()->data(base64_encode(random_bytes(32)));
    }

    public function testGetName()
    {
        $item=TestItemInterface::Create(0,'testItem',base64_encode(random_bytes(32)));
        $this->assertSame('testItem', $item->getName());
    }

    public function testUpdate()
    {
        $item=TestItemInterface::Create(0,'testItem','test item data');
        $this->assertSame('test item data', $item->getData());
        $newData=base64_encode(random_bytes(32));
        $item->update()->data($newData);
        $this->assertSame($newData, $item->getData());
    }

    public function testGetType()
    {
        $item=TestItemInterface::Create(0,'testItem',0x1123A);
        $this->assertIsInt($item->getData());
        $this->assertSame('integer', $item->getType());
    }

    public function testHasNumber()
    {
        $item=TestItemInterface::Create(0,'testItem',0x1123A);
        $this->assertFalse($item->hasNumber());
    }

    public function testGetIndex()
    {
        $item=TestItemInterface::Create(0x0662,'testItem','this is a test item data');
        $this->assertSame(0x0662, $item->getIndex());
    }

    public function testAnon()
    {
        $item=TestItemInterface::Anon(45);
        $this->assertSame(45, $item->getIndex());
        $this->assertNull($item->getData());
        $this->assertSame('',$item->getName());
    }

    public function testDoesThrow()
    {
        $item=TestItemInterface::CreateImmutable(0,'testItem',0x1123A);
        $this->expectException(ImmutableItem::class);
        $item->update()->data(base64_encode(random_bytes(32)));
    }

    public function testGetData()
    {
        $item=TestItemInterface::Create(0,'testItem',0x1123A);
        $this->assertSame(0x1123A, $item->getData());
    }

}
