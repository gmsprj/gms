<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategorysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategorysTable Test Case
 */
class CategorysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategorysTable
     */
    public $Categorys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.categorys',
        'app.guilds'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Categorys') ? [] : ['className' => 'App\Model\Table\CategorysTable'];
        $this->Categorys = TableRegistry::get('Categorys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Categorys);

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
