<?php

use WWON\Restify\Resource\Item;

class ItemTest extends PHPUnit_Framework_TestCase
{

    public function testTranform()
    {
        $book = new Book([
            'id' => 1,
            'name' => 'Book1'
        ]);

        $item = new Item(new BookTransformer);
        $this->assertEquals([
            'id' => 1,
            'name' => 'Book1'
        ], $item->transform($book));
    }

}