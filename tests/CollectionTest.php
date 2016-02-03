<?php

use WWON\Restify\Resource\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{

    public function testTranform()
    {
        $books = [
            new Book([
                'id' => 1,
                'name' => 'Book1'
            ]),
            new Book([
                'id' => 2,
                'name' => 'Book2'
            ]),
            new Book([
                'id' => 3,
                'name' => 'Book3'
            ])
        ];

        $collection = new Collection(new BookTransformer);
        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'Book1'
            ],
            [
                'id' => 2,
                'name' => 'Book2'
            ],
            [
                'id' => 3,
                'name' => 'Book3'
            ]
        ], $collection->transform($books));
    }

}