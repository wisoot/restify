<?php

use WWON\Restify\EmbedManager;
use WWON\Restify\Resource\Embed;

class EmbedManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EmbedManager
     */
    private $embedManager;

    /**
     * setUp method
     */
    public function setUp()
    {
        parent::setUp();

        $this->embedManager = new EmbedManager();
    }

    /**
     * tearDown method
     */
    public function tearDown()
    {
        unset($this->embedManager);

        parent::tearDown();
    }

    public function testFilter()
    {
        // All empty
        $embeds = [];
        $filteringEmbeds = [];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals([], $results);

        // Master list is empty
        $embeds = [];
        $filteringEmbeds = [new Embed('comments')];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals([], $results);

        // Same list with 1 element
        $embeds = [new Embed('comments')];
        $filteringEmbeds = [new Embed('comments')];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(1, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals([], $results[0]->children);

        // Master list has more stuff
        $embeds = [new Embed('comments', [new Embed('users')]), new Embed('likes')];
        $filteringEmbeds = [new Embed('comments')];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(1, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals([], $results[0]->children);

        // Input list has more stuff
        $embeds = [new Embed('comments')];
        $filteringEmbeds = [new Embed('comments', [new Embed('users')])];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(1, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals([], $results[0]->children);

        // Input list has different stuff compare to master
        $embeds = [new Embed('likes')];
        $filteringEmbeds = [new Embed('comments')];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals([], $results);

        // Input list has different stuff compare to master on children level
        $embeds = [new Embed('comments', [new Embed('likes')])];
        $filteringEmbeds = [new Embed('comments', [new Embed('users')])];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(1, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals([], $results[0]->children);

        // Input list has different stuff compare to master on children level pretty complex one
        $embeds = [new Embed('comments', [new Embed('users')]), new Embed('likes', [new Embed('users')])];
        $filteringEmbeds = [new Embed('comments', [new Embed('users')]), new Embed('likes')];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(2, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals(1, count($results[0]->children));
        $this->assertEquals('users', $results[0]->children[0]->name);
        $this->assertEquals('likes', $results[1]->name);
        $this->assertEquals([], $results[1]->children);

        // Input list has different stuff compare to master on children, oh what a mess!
        $embeds = [new Embed('comments', [new Embed('users')]), new Embed('likes', [new Embed('users')])];
        $filteringEmbeds = [new Embed('comments', [new Embed('likes')]), new Embed('likes', [new Embed('guns')])];
        $results = $this->embedManager->filter($embeds, $filteringEmbeds);

        $this->assertEquals(2, count($results));
        $this->assertEquals('comments', $results[0]->name);
        $this->assertEquals([], $results[0]->children);
        $this->assertEquals('likes', $results[1]->name);
        $this->assertEquals([], $results[1]->children);
    }

    public function testGetEmbedsFromParam()
    {
        $embeds = $this->embedManager->getEmbedsFromParam('');

        $this->assertEquals([], $embeds);

        $embeds = $this->embedManager->getEmbedsFromParam('comments');

        $this->assertEquals(1, count($embeds));
        $this->assertEquals('comments', $embeds[0]->name);
        $this->assertEquals([], $embeds[0]->children);

        $embeds = $this->embedManager->getEmbedsFromParam('games.teams.players');

        $this->assertEquals(1, count($embeds));
        $this->assertEquals('games', $embeds[0]->name);
        $this->assertEquals(1, count($embeds[0]->children));
        $this->assertEquals('teams', $embeds[0]->children[0]->name);
        $this->assertEquals(1, count($embeds[0]->children[0]->children));
        $this->assertEquals('players', $embeds[0]->children[0]->children[0]->name);

        $embeds = $this->embedManager->getEmbedsFromParam('comments.users,likes.users');

        $this->assertEquals(2, count($embeds));
        $this->assertEquals('comments', $embeds[0]->name);
        $this->assertEquals(1, count($embeds[0]->children));
        $this->assertEquals('users', $embeds[0]->children[0]->name);
        $this->assertEquals('likes', $embeds[1]->name);
        $this->assertEquals(1, count($embeds[1]->children));
        $this->assertEquals('users', $embeds[1]->children[0]->name);
    }

}