<?php
namespace App\Model\Table;

use App\Model\Entity\Cell;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
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
        return $rules;
    }

    public function findCells($lefts, $types, $rights)
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => $lefts,
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => $rights,
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->where([
                'Cells.name' => sprintf('%s-%s-%s', $lefts, $types, $rights), 
            ]);
    }

    public function addCells($lefts, $types, $rights, $ids)
    {
        $cell = $this->newEntity([
            'name' => $lefts . '-' . $types . '-' . $rights,
            'left_id' => $ids['left_id'],
            'right_id' => $ids['right_id'],
        ]);

        if (!$this->save($cell)) {
            Log::error(json_encode($cell->errors()));
            return false;
        }

        return true;
    }

    public function addTextsNews($arr = [])
    {
        $textTab = TableRegistry::get('Texts');
        $cellsTab = TableRegistry::get('Cells');

        $text = $textTab->newEntity([
            'content' => __($arr['content']),
        ]);

        if (!$textTab->save($text)) {
            $this->Flash->error(__('Internal error'));
            Log::error(json_encode($text->errors()));
            return false;
        }

        $cell = $cellsTab->newEntity([
            'name' => 'texts-news-' . $arr['right'],
            'left_id' => $text->id,
            'right_id' => $arr['rightId'],
        ]);

        if (!$cellsTab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::error(json_encode($cell->errors()));
            $textTab->delete($text);
            return false;
        }

        return true;
    }

    public function findAllTextsNews()
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->select([
                'id' => 'L.id',
                'content' => 'L.content',
                'created' => 'L.created',
            ])->where([
                'Cells.name LIKE' => '%texts-news-%',
            ])->order([
                'L.created' => 'DESC',
            ]);
    }
}
