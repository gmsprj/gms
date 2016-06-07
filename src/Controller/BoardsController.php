<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use DateTime;

class BoardsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['index', 'view', 'post']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['view', 'post']);
    }

    public function index()
    {
        // 板のリスト
        $boards = $this->Boards->find('all');

        // テンプレートを設定
        $this->set('boards', $boards);
    }

    public function view($boardId)
    {
        $this->loadModel('Boards');
        $this->loadModel('Threads');

        // 板のリスト
        $board = $this->Boards->find()
            ->where(['id' => $boardId])
            ->first();

        // スレッドのリスト
        $threads = $this->Threads->find('all')
            ->where(['board_id' => $boardId])
            ->order(['name' => 'DESC']);

        // 認証ユーザーから投稿者ネームを得る
        $authUser = $this->Auth->user();
        $postName = ($authUser == null ? '名無しさん' : $authUser['name']);

        // テンプレートに設定
        $this->set('board', $board);
        $this->set('threads', $threads);
        $this->set('postName', $postName);
    }

    public function post()
    {
        if (!$this->request->is('post')) {
            return;
        }

        $threadName = $this->request->data('threadName');
        $postName = $this->request->data('postName');
        $postContent = $this->request->data('postContent');
        $created = new DateTime(date('Y-m-d H:i:s'));
        $boardId = $this->request->data('boardId');
        $redirect = ['action' => 'view', $boardId];
        
        // スレッドの作成
        $threadsTable = TableRegistry::get('Threads');
        $newThread = $threadsTable->newEntity([
            'name' => $threadName,
            'created' => $created,
            'board_id' => $boardId,
        ]);

        if ($newThread->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newThread->toString());
            $this->redirect($redirect);
            return;
        }
        
        if ($threadsTable->save($newThread)) {
            Log::write('debug', $newThread->toString());
        } else {
            $this->Flash->error(__('登録に失敗しました。'));
            Log::write('error', $newThread->toString());
            $this->redirect($redirect);
            return;
        }

        // ポストの作成
        $postsTable = TableRegistry::get('Posts');
        $newPost = $postsTable->newEntity([
            'name' => $postName,
            'content' => $postContent,
            'thread_id' => $newThread->id,
        ]);
        
        if ($newPost->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newPost->toString());
            $threadsTable->delete($newThread);
            $this->redirect($redirect);
            return;
        }
        
        if ($postsTable->save($newPost)) {
            Log::write('debug', $newPost->toString());
        } else {
            $this->Flash->error(__('登録に失敗しました。'));
            Log::write('error', $newPost->toString());
            $threadsTable->delete($newThread);
        }

        $this->redirect($redirect);
    }
}

?>

