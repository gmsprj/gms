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

    public function addTextsNews($arr = [])
    {
        $textTab = TableRegistry::get('Texts');
        $cellsTab = TableRegistry::get('Cells');

        $text = $textTab->newEntity([
            'content' => __($arr['content']),
        ]);

        if (!$textTab->save($text)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($text->errors()));
            return false;
        }

        $cell = $cellsTab->newEntity([
            'name' => 'texts-news-' . $arr['right'],
            'left_id' => $text->id,
            'right_id' => $arr['rightId'],
        ]);

        if (!$cellsTab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            $textTab->delete($text);
            return false;
        }

        return true;
    }

    public function existsUsersOwners($arr = [])
    {
        $el = $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'users',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => $arr['right'],
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->select([
                'id' => 'L.id',
            ])->where([
                'Cells.name LIKE' => 'users-owners-' . $arr['right'],
                'Cells.left_id' => $arr['id'],
            ])->first();
        return $el != null;
    }

    public function addUsersOwners($arr = [])
    {
        $cellsTab = TableRegistry::get('Cells');

        $cell = $cellsTab->newEntity([
            'name' => 'users-owners-' . $arr['right'],
            'left_id' => $arr['id'],
            'right_id' => $arr['rightId'],
        ]);

        if (!$cellsTab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            return false;
        }

        return true;
    }
    public function findTextsNewsAll($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->select([
                'content' => 'L.content',
                'created' => 'L.created',
            ])->where([
                'Cells.name LIKE' => '%texts-news-%',
            ])->order([
                'L.created' => 'DESC',
            ]);
    }

    public function findTextsNews($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->select([
                'content' => 'L.content',
                'created' => 'L.created',
            ])->where([
                'Cells.name' => 'texts-news-' . $arr['right'],
            ])->order([
                'L.created' => 'DESC',
            ]);
    }

    public function findImagesSyms($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'images',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->select([
                'url' => 'L.url',
            ])->where([
                'Cells.name' => 'images-syms-' . $arr['right'],
            ]);
    }

    /**
     * @param $arr['right']
     * @param $arr['rightId']
     * @param $arr['state']
     */
    public function findDocsOwners($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'docs',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => 'guilds',
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->where([
                'Cells.name' => 'docs-owners-' . $arr['right'],
                'R.id' => $arr['rightId'],
                'L.state' => $arr['state'],
            ]);
    }

    public function findBoardsOwners($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'boards',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => $arr['right'],
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->where([
                'Cells.name' => 'boards-owners-' . $arr['right'],
                'R.id' => $arr['rightId'],
            ]);
    }

    public function findUsersOwners($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'users',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => $arr['right'],
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->where([
                'Cells.name' => 'users-owners-' . $arr['right'],
            ]);
    }

    public function findKVSAll($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->join([
                'table' => 'texts',
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id'
            ])->select([
                'key' => 'L.content',
                'value' => 'R.content',
            ])->where([
                'Cells.name LIKE' => '%-kvs-%',
            ]);
    }

    public function findThreadsRefs($arr = [])
    {
        return $this->find()
            ->hydrate(false)
            ->join([
                'table' => 'threads',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->where([
                'Cells.name' => 'threads-refs-' . $arr['right'],
                'Cells.right_id' => $arr['rightId'],
            ]);
    }
}
