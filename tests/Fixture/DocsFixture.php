<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DocsFixture
 *
 */
class DocsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '文書のID', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => '', 'comment' => '文書の名前', 'precision' => null, 'fixed' => null],
        'content' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '文書の内容', 'precision' => null],
        'state' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => 'closed', 'comment' => '文書の状態', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '文書の作成日', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '文書の更新日', 'precision' => null],
        'guild_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '所属ギルドの外部キー', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'guild_id' => ['type' => 'index', 'columns' => ['guild_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'guild_docs_ibfk_1' => ['type' => 'foreign', 'columns' => ['guild_id'], 'references' => ['guilds', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'state' => 'Lorem ipsum dolor sit amet',
            'created' => '2016-06-30 18:50:56',
            'modified' => '2016-06-30 18:50:56',
            'guild_id' => 1
        ],
    ];
}
