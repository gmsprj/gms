<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BoardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BoardsTable Test Case
 */
class BoardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BoardsTable
     */
    public $Boards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.boards',
        'app.threads',
        'app.posts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Boards') ? [] : ['className' => 'App\Model\Table\BoardsTable'];
        $this->Boards = TableRegistry::get('Boards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Boards);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
