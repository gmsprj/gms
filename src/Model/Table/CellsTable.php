<?php
namespace App\Model\Table;

use App\Model\Entity\Cell;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cells Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Lefts
 * @property \Cake\ORM\Association\BelongsTo $Rights
 */
class CellsTable extends Table
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

        $this->table('cells');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('Lefts', [
            'foreignKey' => 'left_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Rights', [
            'foreignKey' => 'right_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['left_id'], 'Lefts'));
        $rules->add($rules->existsIn(['right_id'], 'Rights'));
        return $rules;
    }
}
