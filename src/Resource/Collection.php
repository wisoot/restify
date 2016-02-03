<?php

namespace WWON\Restify\Resource;

class Collection extends Item
{

    /**
     * get array result of transformation of the given data
     *
     * @param mixed $data
     * @param array $embeds
     * @return array
     */
    public function transform($data, array $embeds = [])
    {
        $result = [];

        foreach ($data as $item) {
            $result[] = parent::transform($item, $embeds);
        }

        return $result;
    }

}