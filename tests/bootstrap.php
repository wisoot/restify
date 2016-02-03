<?php

use WWON\Restify\Resource\Transformer;

if (! @include_once __DIR__ . '/../vendor/autoload.php') {
    exit("You must set up the project dependencies, run the following commands:\n> wget http://getcomposer.org/composer.phar\n> php composer.phar install\n");
}

class Author
{

    public $id;

    public $name;

    public $books = [];

    public function __construct($inputs = [])
    {
        if (!empty($inputs['id'])) {
            $this->id = $inputs['id'];
        }

        if (!empty($inputs['name'])) {
            $this->name = $inputs['name'];
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}

class Book
{

    public $id;

    public $name;

    public $author;

    public function __construct($inputs = [])
    {
        if (!empty($inputs['id'])) {
            $this->id = $inputs['id'];
        }

        if (!empty($inputs['name'])) {
            $this->name = $inputs['name'];
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}

class AuthorTransformer extends Transformer
{

    public function books()
    {
        return $this->hasMany(BookTransformer::class);
    }

}

class BookTransformer extends Transformer
{

    public function author()
    {
        return $this->belongsTo(AuthorTransformer::class);
    }

}