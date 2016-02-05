<?php

namespace WWON\Restify;

use WWON\Restify\Resource\Collection;
use WWON\Restify\Resource\Item;

class Converter
{

    /**
     * @var array
     */
    protected $availableEmbeds;

    /**
     * @var EmbedManager
     */
    protected $embedManager;

    /**
     * Converter constructor
     *
     * @param array|string $availableEmbeds
     */
    public function __construct($availableEmbeds)
    {
        if (empty($this->embedManager)) {
            $this->embedManager = new EmbedManager;
        }

        if (is_string($availableEmbeds)) {
            $availableEmbeds = $this->embedManager
                ->getEmbedsFromParam($availableEmbeds);
        }

        $this->availableEmbeds = $availableEmbeds;
    }

    /**
     * setEmbedManager method
     *
     * @param EmbedManager $embedManager
     */
    public function setEmbedManager(EmbedManager $embedManager)
    {
        $this->embedManager = $embedManager;
    }

    /**
     * convert data into transformed array
     *
     * @param mixed $data
     * @param Item|Collection $resource
     * @param array|string $embeds
     * @return array
     */
    public function convert($data, Item $resource, $embeds = [])
    {
        if (is_string($embeds)) {
            $embeds = $this->embedManager
                ->getEmbedsFromParam($embeds);
        }

        $embeds = $this->embedManager
            ->filter($this->availableEmbeds, $embeds);

        return $resource->transform($data, $embeds);
    }

}