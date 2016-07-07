<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Docs Controller
 *
 * ドキュメントを管理するアプリケーション。
 *
 * @property \App\Model\Table\DocsTable $Docs
 */
class DocsController extends AppController
{
    public $paginate = [
        'page' => 1,
        'limit' => 10,
        'maxLimit' => 100,
        'fields' => [
            'id', 'name', 'description'
        ],
        'sortWhitelist' => [
            'id', 'name', 'description'
        ]
    ];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
        $this->Auth->allow(['add', 'edit']);// TODO: デバッグ用の allow
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $query = $this->request->query;

        if ($this->request->is('get')) {
            $owners = (isset($query['owners']) ? $query['owners'] : null);
            $ownerId = (isset($query['ownerId']) ? $query['ownerId'] : null);
            $state = (isset($query['state']) ? $query['state'] : null);

            if ($owners) {
                $where = [];
                if ($ownerId) {
                    $where['R.id'] = $ownerId;
                }
                if ($state) {
                    $where['L.state'] = $state;
                }

                $docs = $this->Cells->findCells('docs', 'owners', $owners)
                    ->select([
                        'id' => 'L.id',
                        'name' => 'L.name',
                        'state' => 'L.state',
                        'created' => 'L.created',
                        'modified' => 'L.modified',
                        'ownerId' => 'R.id',
                        'ownerName' => 'R.name',
                    ])->where(
                        $where
                    )->all();
            } else {
                $docs = $this->Docs->find()->all();
            }

            $this->set('docs', $docs);
            $this->set('_serialize', [
                'docs',
            ]);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Docs id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($this->request->is('get')) {
            $owners = (isset($query['owners']) ? $query['owners'] : 'guilds');

            $doc = $this->Cells->findCells('docs', 'owners', $owners)
                ->select([
                    'id' => 'L.id',
                    'name' => 'L.name',
                    'state' => 'L.state',
                    'content' => 'L.content',
                    'created' => 'L.created',
                    'modified' => 'L.modified',
                    'ownerId' => 'R.id',
                    'ownerName' => 'R.name',
                ])->where([
                    'L.id' => $id,
                ])->first();

            $this->set('doc', $doc);
            $this->set('_serialize', [
                'doc',
            ]);
        }
    }

    /**
     * Add method
     */
    public function add()
    {
        $failTo = ['controller' => 'Docs', 'action' => 'index'];
        $doneTo = ['controller' => 'Docs', 'action' => 'index'];

        if (!$this->request->is('post')) {
            return $this->redirect($failTo);
        }

        $guildId = $this->request->data('guildId'); // 所属ギルド
        $threadId = $this->request->data('threadId'); // 参照先スレッド
        $docName = $this->request->data('docName');
        $docContent = $this->request->data('docContent');
        $docState = $this->request->data('docState');

        // 必須パラメーターのチェック
        if (!$guildId || !$docName || !$docContent || !$docState) {
            $this->Flash->error(__('不正な入力です。'));
            return $this->redirect($failTo);
        }

        // Users
        $user = $this->Auth->user();
        if (!$user) {
            $this->Flash->error(__('文書の提案にはサインインが必要です。'));
            return $this->redirect($failTo);
        }

        // Guilds
        $guild = $this->Guilds->get($guildId);
        if (!$guild) {
            $this->Flash->error(__('ギルドが見つかりません。'));
            Log::error('Not found guild id ' + $guildId);
            return $this->redirect($failTo);
        }
        
        // Docs
        $tab = TableRegistry::get('Docs');
        $doc = $tab->newEntity([
            'name' => $docName,
            'content' => $docContent,
            'state' => $docState,
        ]);

        if ($doc->errors()) {
            $this->Flash->error(__('Invalid input data'));
            Log::error(json_encode($doc->errors()));
            return $this->redirect($failTo);
        }

        if (!$tab->save($doc)) {
            $this->Flash->error(__('Failed to save'));
            Log::error(json_encode($doc->errors()));
            return $this->redirect($failTo);
        }

        // 参照先スレッド（オプション）
        if ($threadId) {
            $tab = TableRegistry::get('Cells');
            $cell = $tab->newEntity([
                'name' => 'threads-refs-docs',
                'left_id' => $threadId,
                'right_id' => $doc->id,
            ]);

            if (!$tab->save($cell)) {
                $this->Flash->error(__('Failed to save'));
                Log::error(json_encode($cell->errors()));
                return $this->redirect($failTo);
            }
        }

        // Cells で繋ぐ
        $this->Cells->addCells('docs', 'owners', 'guilds', [
            'left_id' => $doc->id,
            'right_id' => $guildId,
        ]);

        // ニュースを発信
        $this->Cells->addTextsNews([
            'right' => 'guilds',
            'rightId' => $guildId,
            'content' => sprintf('%sで「%s」が提案されました。', $guild->name, $docName)
        ]);

        return $this->redirect($doneTo);
    }

    /**
     * Edit method
     *
     * @param string|null $id Docs id.
     */
    public function edit($id = null)
    {
        $failTo = ['controller' => 'Docs', 'action' => 'index'];

        if ($this->request->is('post')) {
            // Get parameters
            $id = $this->request->data('id');
            $name = $this->request->data('name');
            $content = $this->request->data('content');
            $guildId = $this->request->data('guildId');
            $threadId = $this->request->data('threadId');

            $doneTo = ['controller' => 'Docs', 'action' => 'view', $id];

            // Update parameters
            $doc = $this->Docs->get($id);
            $oldName = $doc->name;
            $doc->name = $name;
            $doc->content = $content;

            // Save
            $docsTab = TableRegistry::get('Docs');
            if (!$docsTab->save($doc)) {
            }

            // Update cells for docs and guilds relationship
            $cell = $this->Cells->findCells('docs', 'owners', 'guilds')
                ->where([
                    'L.id' => $id,
                ])->first();
            if (!$cell) {
            }
            $cellsTab = TableRegistry::get('Cells');
            $cellEntity = $cellsTab->get($cell['id']);
            $cellEntity->right_id = $guildId;
            if (!$cellsTab->save($cellEntity)) {
            }

            // News
            if (!$this->Cells->addTextsNews([
                'right' => 'guilds',
                'rightId' => $guildId,
                'content' => __(sprintf('文書「%s」が更新されました。', $oldName))
            ])) {
            }

            $this->redirect($doneTo);
        }
    }
}

