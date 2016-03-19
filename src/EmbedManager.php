<?php

namespace WWON\Restify;

use WWON\Restify\Resource\Embed;

class EmbedManager
{

    /**
     * filter method
     *
     * @param array $embeds
     * @param array $filteringEmbeds
     * @return array
     */
    public function filter(array $embeds, array $filteringEmbeds)
    {
        $results = [];

        foreach ($filteringEmbeds as $filteringEmbed) {
            foreach ($embeds as $embed) {
                if ($filteringEmbed->name == $embed->name) {
                    $results[] = $this->deepFilter($embed, $filteringEmbed);
                }
            }
        }

        return $results;
    }

    /**
     * deepFilter method
     *
     * @param Embed $embed
     * @param Embed $filteringEmbed
     * @return Embed
     */
    protected function deepFilter(Embed $embed, Embed $filteringEmbed)
    {
        $children = [];

        foreach ($filteringEmbed->children as $filteringEmbedChild) {
            foreach ($embed->children as $embedChild) {
                if ($filteringEmbedChild->name == $embedChild->name) {
                    $children[] = $this->deepFilter($embedChild, $filteringEmbedChild);
                }
            }
        }

        $filteringEmbed->children = $children;

        return $filteringEmbed;
    }

    /**
     * getEmbedsFromParam method
     *
     * @param string $embedString
     * @return array
     */
    public function getEmbedsFromParam($embedString)
    {
        if (empty($embedString)) {
            return [];
        }

        $embeds = [];
        $items = explode(',', $embedString);

        foreach ($items as $item) {
            $embeds[] = $this->getAnEmbed(explode('.', $item));
        }

        return $embeds;
    }

    /**
     * getAnEmbed method
     *
     * @param array $embedItems
     * @return Embed
     */
    protected function getAnEmbed(array $embedItems = [])
    {
        $item = array_shift($embedItems);

        if (count($embedItems)) {
            $children = [$this->getAnEmbed($embedItems)];
        } else {
            $children = [];
        }

        return new Embed($item, $children);
    }

}