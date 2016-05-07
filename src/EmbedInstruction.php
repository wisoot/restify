<?php

namespace WWON\Restify;

class EmbedInstruction
{

    /**
     * @var array
     */
    public $embeds = [];

    /**
     * @var array
     */
    public $availableEmbeds = [];

    /**
     * Request constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $attribute = camel_case($key);

            if (property_exists($this, $attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

}