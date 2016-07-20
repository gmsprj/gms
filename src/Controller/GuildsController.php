<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Guilds Controller
 *
 * ギルドを管理するアプリケーション。
 *
 * @property \App\Model\Table\GuildsTable $Guilds
 */
class GuildsController extends AppController
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
        $this->Auth->allow([
            'entry',
            'leave',
        ]);
        $this->loadModel('Category');
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if ($this->request->is('get')) {
            $guilds = $this->Guilds->find()->all();

            $this->set('guilds', $guilds);
            $this->set('_serialize', [
                'guilds',
            ]);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Guild id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($this->request->is('get')) {
            $guild = $this->Guilds->get($id);
            $guild['images'] = $this->Cells->findCells('images', 'syms', 'guilds')
                ->select([
                    'id' => 'L.id',
                    'url' => 'L.url',
                ])->where([
                    'R.id' => $id,
                ])->all();

            $this->set('guild', $guild);
            $this->set('_serialize', [
                'guild',
            ]);
        }
    }

    /**
     * Entry method
     *
     * ギルドへの入会を処理する。
     * 入会に失敗した場合、/guilds/index へリダイレクト。
     * 入会に成功した場合、入会先のギルドへリダイレクト。
     *
     * @param string request->data('userId') ユーザーID
     * @param string request->data('guildId') ギルドID
     */
    public function entry()
    {
        // パラメーターの取得
        $userId = $this->request->data('userId');
        $guildId = $this->request->data('guildId');

        // リダイレクト先
        $failTo = ['controller' => 'Guilds', 'action' => 'index'];
        $doneTo = ['action' => 'view', $guildId];

        // メソッド名をチェック
        if (!$this->request->is('post')) {
            Log::error(__('Invalid method of '. $this->request->method()));
            return $this->redirect($failTo);
        }

        // ギルドの取得/チェック
        $guild = $this->Guilds->get($guildId);
        if (!$guild) {
            Log::error(__('Invalid Guilds ID of ' . $guildId));
            return $this->redirect($failTo);
        }

        // ユーザーの取得/チェック
        $usersTab = TableRegistry::get('Users');
        $user = $usersTab->get($userId);
        if (!$user) {
            Log::error(__('Invalid Users ID of ' . $userId));
            return $this->redirect($failTo);
        }

        // 入会
        if ($user->hasOwner($this->Cells, 'guilds', $guildId)) {
            $this->Flash->error(__('既に入会済みです。'));
            return $this->redirect($doneTo);
        } else {
            $this->Cells->addCells('users', 'owners', 'guilds', [
                'left_id' => $userId,
                'right_id' => $guildId,
            ]);
        }

        // 保存
        if (!$usersTab->save($user)) {
            Log::error(__('Failed to save Users of ID ' . $user->id));
            Log::error(json_encode($user->errors()));
            return $this->redirect($failTo);
        }

        // セッションの更新
        $this->Auth->setUser($user->toArray());

        // ニュースを発信
        $this->Cells->addTextsNews([
            'right' => 'guilds',
            'rightId' => $guildId,
            'content' => __(sprintf('ユーザー「%s」がギルド「%s」に入会しました。', $user->name, $guild->name)),
        ]);

        $this->Flash->success(__('入会しました。'));
        return $this->redirect($doneTo);
    }

    /**
     * Leave method
     *
     * ギルドからの退会を処理する。
     * 退会に失敗した場合、/guilds/index へリダイレクト。
     * 退会に成功した場合、退会したギルドへリダイレクト。
     *
     * @param string request->data('userId') ユーザーID
     * @param string request->data('guildId') ギルドID
     */
    public function leave()
    {
        // パラメーターの取得
        $userId = $this->request->data('userId');
        $guildId = $this->request->data('guildId');

        // リダイレクト先
        $failTo = ['action' => 'index'];
        $doneTo = ['controller' => 'Users', 'action' => 'view', $userId];

        // 退会
        $cells = $this->Cells->findCells('users', 'owners', 'guilds')
            ->where([
                'R.id' => $guildId,
            ])->all();
        //Log::debug($cells);

        $cellsTab = TableRegistry::get('Cells');
        foreach ($cells as $el) {
            $entity = $cellsTab->get($el['id']);
            $cellsTab->delete($entity);
        }

        return $this->redirect($doneTo);
    }
}

