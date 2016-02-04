<?php

use WWON\Restify\Resource\Embed;
use WWON\Restify\Resource\Item;

class EmbedTest extends PHPUnit_Framework_TestCase
{

    public function testTransformWithEmbed()
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

        $author = new Author([
            'id' => 4,
            'name' => 'James'
        ]);
        $author->books = $books;

        $embed = new Embed('books');
        $item = new Item(new AuthorTransformer);

        $this->assertEquals([
            'id' => 4,
            'name' => 'James',
            'books' => [
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
            ]
        ], $item->transform($author, [$embed]));
    }

}