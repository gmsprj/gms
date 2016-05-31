<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Thread Entity.
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $created
 * @property int $board_id
 * @property \App\Model\Entity\Board $board
 * @property \App\Model\Entity\Post[] $posts
 */
class Thread extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    public function toString()
    {
	return sprintf("id[%d] name[%s] created[%s] board_id[%d]",
		$this->id, $this->name, $this->created, $this->board_id);
    }

    public function countPosts()
    {
	$this->Posts = TableRegistry::get('Posts');
	return $this->Posts->find()
		->where(['thread_id' => $this->id])
		->count()
		;
    }
}
