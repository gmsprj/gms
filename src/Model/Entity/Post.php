<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $thread_id
 * @property \App\Model\Entity\Thread $thread
 */
class Post extends Entity
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
 
    public function toString() {
        return sprintf('id[%d] name[%s] created[%s] modified[%s] content[%s] errors[%s]',
                $this->id, $this->name, json_encode($this->created), json_encode($this->modified), $this->content, json_encode($this->errors()));
    }

}
