<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'ユーザーのID', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'comment' => 'ユーザーの名前', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'comment' => 'ユーザーのメールアドレス', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'comment' => 'ユーザーのログイン・パスワード', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => 'ユーザーの作成日', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => 'ユーザーの更新日', 'precision' => null],
        'guild_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '所属ギルドの外部キー', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'guild_id' => ['type' => 'index', 'columns' => ['guild_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'guild_ibfk_1' => ['type' => 'foreign', 'columns' => ['guild_id'], 'references' => ['guilds', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'email' => 'Lorem ipsum dolor sit amet',
            'password' => 'Lorem ipsum dolor sit amet',
            'created' => '2016-06-09 08:16:11',
            'modified' => '2016-06-09 08:16:11',
            'guild_id' => 1
        ],
    ];
}
