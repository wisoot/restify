<?php

namespace WWON\Restify\Resource;

class Embed
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var null|Embed
     */
    public $children;

    /**
     * Embed constructor
     *
     * @param string $name
     * @param array $children
     */
    public function __construct($name, $children = [])
    {
        $this->name = $name;
        $this->children = $children;
    }

}