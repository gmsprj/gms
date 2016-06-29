<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TextsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TextsTable Test Case
 */
class TextsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TextsTable
     */
    public $Texts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.texts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Texts') ? [] : ['className' => 'App\Model\Table\TextsTable'];
        $this->Texts = TableRegistry::get('Texts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Texts);

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
