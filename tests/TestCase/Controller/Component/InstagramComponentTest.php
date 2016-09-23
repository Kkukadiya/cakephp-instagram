<?php
namespace Instagram\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use Instagram\Controller\Component\InstagramComponent;

/**
 * Instagram\Controller\Component\InstagramComponent Test Case
 */
class InstagramComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Instagram\Controller\Component\InstagramComponent
     */
    public $Instagram;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Instagram = new InstagramComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Instagram);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
