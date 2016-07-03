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
        $news = $this->findTextsNewsAll()
            ->limit(5);
        $symbol = $this->findImagesSyms([
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

        $this->set('guilds', $this->Guilds->find('all'));
        $this->set('news', $news);
        $this->set('symbol', $symbol);
        $this->set('customDocs', $customDocs);
        $this->set('_serialize', [
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
        $guild = $this->Guilds->get($id);
        $boards = $this->findBoardsOwners([
                'right' => 'guilds',
                'rightId' => $id,
            ])->all();
        $guildSymbols = $this->findImagesSyms([
                'right' => 'guilds',
                'rightId' => $id,
            ])->all();
        $news = $this->findTextsNews([
                'right' => 'guilds',
            ])->limit(5);
        $publishedDocs = $this->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'published'
            ])->all();
        $draftDocs = $this->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'draft'
            ])->all();
        $counterDocs = $this->findDocsOwners([
                'right' => 'guilds',
                'rightId' => $id,
                'state' => 'counter'
            ])->all();

        $this->set('guild', $guild);
        $this->set('guildSymbols', $guildSymbols);
        $this->set('boards', $boards);
        $this->set('news', $news);
        $this->set('publishedDocs', $publishedDocs);
        $this->set('draftDocs', $draftDocs);
        $this->set('counterDocs', $counterDocs);
        $this->set('_serialize', [
            'guild',
            'guildSymbols',
            'boards',
            'news',
            'publishedDocs',
            'draftDocs',
            'counterDocs',
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
        // エラー時のリダイレクト先
        $redirect = ['controller' => 'Guilds', 'action' => 'index'];

        // メソッド名をチェック
        if (!$this->request->is('post')) {
            Log::write('error', __('Invalid method of '. $this->request->method()));
            return $this->redirect($redirect);
        }

        // パラメーターの取得
        $userId = $this->request->data('userId');
        $guildId = $this->request->data('guildId');

        // ユーザーの取得/チェック
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->get($userId);
        if (!$user) {
            Log::write('error', __('Invalid Users ID of ' . $userId));
            return $this->redirect($redirect);
        }

        // 入会
        $user->guild_id = $guildId;
        if (!$usersTable->save($user)) {
            Log::write('error', __('Failed to save Users of ID ' . $user->id));
            Log::write('error', json_encode($user->errors()));
            return $this->redirect($redirect);
        }

        // セッションの更新
        $this->Auth->setUser($user->toArray());

        $this->Flash->success(__('入会しました。'));
        return $this->redirect(['action' => 'view', $guildId]);
    }
}

