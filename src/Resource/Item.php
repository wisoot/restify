<?php

namespace WWON\Restify\Resource;

class Item
{

    /**
     * @var Transformer
     */
    protected $transformer;

    /**
     * Resource constructor
     *
     * @param Transformer $transformer
     */
    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * convert method
     *
     * @param $entity
     * @param array $embeds
     * @return array
     */
    public function transform($entity, array $embeds = [])
    {
        $data = $this->transformer->transform($entity);

        foreach ($embeds as $embed) {

            if (isset($entity->{$embed->name})
                && method_exists($this->transformer, $embed->name)) {

                $resource = $this->transformer->{$embed->name}();
                $data[$embed->name] = $resource
                    ->transform($entity->{$embed->name}, $embed->children);
            }

        }

        return $data;
    }

}