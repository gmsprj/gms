<?php
namespace App\Model\Table;

use App\Model\Entity\Board;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Boards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentBoards
 * @property \Cake\ORM\Association\HasMany $ChildBoards
 * @property \Cake\ORM\Association\HasMany $Threads
 */
class BoardsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('boards');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentBoards', [
            'className' => 'Boards',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildBoards', [
            'className' => 'Boards',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Threads', [
            'foreignKey' => 'board_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('description');

        $validator
            ->allowEmpty('parent_name');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentBoards'));
        return $rules;
    }
}
