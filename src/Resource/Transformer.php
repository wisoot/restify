<?php

namespace WWON\Restify\Resource;

abstract class Transformer
{

    /**
     * transform entity from object or array into output array
     *
     * @param mixed $entity
     * @return array
     */
    public function transform($entity)
    {
        if (method_exists($entity, 'toArray')) {
            return $entity->toArray();
        }

        return (array) $entity;
    }

    /**
     * hasOne method
     *
     * @param string $related
     * @return Item
     */
    protected function hasOne($related)
    {
        return new Item(new $related());
    }

    /**
     * hasMany method
     *
     * @param string $related
     * @return Collection
     */
    protected function hasMany($related)
    {
        return new Collection(new $related());
    }

    /**
     * belongsTo method
     *
     * @param string $related
     * @return Item
     */
    protected function belongsTo($related)
    {
        return new Item(new $related());
    }

}