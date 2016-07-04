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
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
        $this->Auth->allow(['entry']);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Docs');
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $news = $this->Cells->findTextsNewsAll()
            ->limit(5);
        $symbol = $this->Cells->findImagesSyms([
                'right' => 'sites',
            ])->first();
        $customDocs = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'docs',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->join([
                'table' => 'guilds',
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id'
            ])->select([
                'guildId' => 'R.id',
                'guildName' => 'R.name',
                'docId' => 'L.id',
                'docName' => 'L.name',
            ])->where([
                'Cells.name' => 'docs-owners-guilds'
            ])->all();
        $site = $this->Sites->get(1);
        $threads = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'boards',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => 'threads',
                'alias' => 'T',
                'type' => 'INNER',
                'conditions' => 'L.id = T.board_id',
            ])->select([
                'id' => 'T.id',
                'name' => 'T.name',
            ])->where([
                'Cells.name' => 'boards-owners-sites',
                'Cells.right_id' => $site->id,
            ])->all();

        $this->set('guilds', $this->Guilds->find('all'));
        $this->set('news', $news);
        $this->set('threads', $threads);
        $this->set('symbol', $symbol);
        $this->set('customDocs', $customDocs);
        $this->set('_serialize', [
            'site',
            'board',
            'threads',
            'guilds',
            'news',
            'symbol',
            'customDocs'
        ]);
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
        $user = $this->Auth->user();
        $guild = $this->Guilds->get($id);
        $boards = $this->Cells->findBoardsOwners([
                'right' => 'guilds',
                'rightId' => $id,
            ])->all();
        $guildSymbols = $this->Cells->findImagesSyms([
                'right' => 'guilds',
                'rightId' => $id,
            ])->all();
        $news = $this->Cells->findTextsNews([
                'right' => 'guilds',
            ])->limit(5);
        $publishedDocs = $this->Cells->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'published'
            ])->all();
        $draftDocs = $this->Cells->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'draft'
            ])->all();
        $counterDocs = $this->Cells->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'counter'
            ])->all();

        $this->set('user', $user);
        $this->set('guild', $guild);
        $this->set('guildSymbols', $guildSymbols);
        $this->set('boards', $boards);
        $this->set('news', $news);
        $this->set('publishedDocs', $publishedDocs);
        $this->set('draftDocs', $draftDocs);
        $this->set('counterDocs', $counterDocs);
        $this->set('wasEntry', $this->Cells->existsUsersOwners([
            'right' => 'guilds',
            'id' => $user['id'],
        ]));
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
            'user',
            'guild',
            'guildSymbols',
            'boards',
            'news',
            'publishedDocs',
            'draftDocs',
            'counterDocs',
            'wasEntry',
            'csrf',
        ]);
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
            Log::write('error', __('Invalid method of '. $this->request->method()));
            return $this->redirect($failTo);
        }

        // ユーザーの取得/チェック
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->get($userId);
        if (!$user) {
            Log::write('error', __('Invalid Users ID of ' . $userId));
            return $this->redirect($failTo);
        }

        // 入会
        $arr = [
            'right' => 'guilds',
            'id' => $userId,
        ];
        if ($this->Cells->existsUsersOwners($arr)) {
            $this->Flash->error(__('既に入会済みです。'));
            return $this->redirect($doneTo);
        } else {
            $this->Cells->addUsersOwners($arr);
        }

        // 保存
        if (!$usersTable->save($user)) {
            Log::write('error', __('Failed to save Users of ID ' . $user->id));
            Log::write('error', json_encode($user->errors()));
            return $this->redirect($failTo);
        }

        // セッションの更新
        $this->Auth->setUser($user->toArray());

        $this->Flash->success(__('入会しました。'));
        return $this->redirect($doneTo);
    }
}

