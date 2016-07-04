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
        $this->Auth->allow([
            'entry',
            'leave',
        ]);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Posts');
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
        $news = $this->Cells->findAllTextsNews()
            ->limit(5);
        $symbol = $this->Cells->findCells('images', 'syms', 'sites')
            ->select([
                'url' => 'L.url',
            ])->first();
        $customDocs = $this->Cells->findCells('docs', 'owners', 'guilds')
            ->select([
                'guildId' => 'R.id',
                'guildName' => 'R.name',
                'docId' => 'L.id',
                'docName' => 'L.name',
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
        $boards = $this->Cells->findCells('boards', 'owners', 'guilds')
            ->where([
                'R.id' => $id,
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->all();
        $guildSymbols = $this->Cells->findCells('images', 'syms', 'guilds')
            ->where([
                'R.id' => $id,
            ])->select([
                'url' => 'L.url',
            ])->all();
        $publishedDocs = $this->Cells->findCells('docs', 'owners', 'guilds')
            ->where([
                'R.id' => $id,
                'L.state' => 'published',
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->all();
        $draftDocs = $this->Cells->findCells('docs', 'owners', 'guilds')
            ->where([
                'R.id' => $id,
                'L.state' => 'draft',
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->all();
        $news = $this->Cells->findCells('texts', 'news', 'guilds')
            ->where([
                'R.id' => $id,
            ])->select([
                'content' => 'L.content',
                'created' => 'L.created',
            ])->limit(5);
        $wasEntry = $this->Cells->findCells('users', 'owners', 'guilds')
            ->where([
                'R.id' => $id,
            ])->first();
        $headlineBoard = $this->Cells->findCells('boards', 'owners', 'guilds')
            ->where([
                'R.id' => $id,
            ])->select([
                'id' => 'L.id',
            ])->order([
                'L.created' => 'DESC',
            ])->first();
        $headlineThread = $this->Threads->find()
            ->select([
                'id',
                'name',
            ])->where([
                'board_id' => $headlineBoard['id'],
            ])->order([
                'created' => 'DESC',
            ])->first();
        $headlinePosts = $this->Posts->find()
            ->select([
                'name',
                'content',
            ])->where([
                'thread_id' => $headlineThread->id,
            ])->order([
                'created' => 'DESC',
            ])->limit(5);

        $this->set('user', $user);
        $this->set('guild', $guild);
        $this->set('guildSymbols', $guildSymbols);
        $this->set('boards', $boards);
        $this->set('news', $news);
        $this->set('headlineThread', $headlineThread);
        $this->set('headlinePosts', $headlinePosts);
        $this->set('publishedDocs', $publishedDocs);
        $this->set('draftDocs', $draftDocs);
        $this->set('wasEntry', $wasEntry);
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
            'user',
            'guild',
            'guildSymbols',
            'boards',
            'news',
            'headlineThread',
            'headlinePosts',
            'publishedDocs',
            'draftDocs',
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

        // ギルドの取得/チェック
        $guild = $this->Guilds->get($guildId);
        if (!$guild) {
            Log::error(__('Invalid Guilds ID of ' . $guildId));
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
        if (!$usersTable->save($user)) {
            Log::write('error', __('Failed to save Users of ID ' . $user->id));
            Log::write('error', json_encode($user->errors()));
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
     * ギルドからの入会を処理する。
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
        //Log::write('debug', $cells);

        $cellsTab = TableRegistry::get('Cells');
        foreach ($cells as $el) {
            $entity = $cellsTab->get($el['id']);
            $cellsTab->delete($entity);
        }

        return $this->redirect($doneTo);
    }
}

