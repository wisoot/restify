<?php

use WWON\Restify\Converter;
use WWON\Restify\Resource\Item;

class ConverterTest extends PHPUnit_Framework_TestCase
{

    public function testConvert()
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

        $converter = new Converter('books');

        $this->assertEquals([
            'id' => 4,
            'name' => 'James'
        ], $converter->convert($author, new Item(new AuthorTransformer)));

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
        ], $converter->convert($author, new Item(new AuthorTransformer), 'books'));
    }

}