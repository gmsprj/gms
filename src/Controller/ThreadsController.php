<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use DateTime;

/**
 * Threads
 *
 * スレッドを管理するアプリケーション。
 *
 * @see src/Controller/Boards.php
 * @see src/Controller/Posts.php
 */
class ThreadsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['index', 'view', 'add']);
        $this->loadModel('Boards');
        $this->loadModel('Posts');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $threads = $this->Threads->find('all');
        $this->set('threads', $threads);
    }

    /**
     * View method
     *
     * @param string|null $threadId Threads id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($threadId)
    {
        $thread = $this->Threads->find()
            ->where(['id' => $threadId])
            ->first();
        if (!$thread) {
            throw new NotFoundException(__('スレッドが見つかりません。'));
        }

        $board = $this->Boards->find()
            ->where(['id' => $thread->board_id])
            ->first();

        $threads = $this->Threads->find('all')
            ->where(['board_id' => $thread->board_id]);
        
        $posts = $this->Posts->find('all')
            ->where(['thread_id' => $thread->id]);

        // TODO: 板毎にデフォルトの「名無しさん」等が必要になった場合はここを変更
        $authUser = $this->Auth->user();
        $postName = ($authUser ? $authUser['name'] : '名無しさん');

        // テンプレートに設定
        $this->set('thread', $thread);
        $this->set('board', $board);
        $this->set('threads', $threads);
        $this->set('posts', $posts);
        $this->set('postName', $postName);
    }

    /**
     * Add method
     *
     * 新規スレッドの作成。
     */
    public function add()
    {
        // メソッド名のチェック
        if (!$this->request->is('post')) {
            Log::write('error', 'Invalid method of ' . $this->request->method());
            return $this->redirect(['controller' => 'Plazas', 'action' => 'index']);
        }

        // パラメーターの取得
        $threadName = $this->request->data('threadName');
        $postName = $this->request->data('postName');
        $postContent = $this->request->data('postContent');
        $boardId = $this->request->data('boardId');
        $authUser = $this->Auth->user();
        $created = new DateTime(date('Y-m-d H:i:s'));
        $redirect = ['controller' => 'Boards', 'action' => 'view', $boardId];

        // 板の親が guilds でかつ、認証ユーザーでないなら書き込み不可
        $board = $this->Boards->get($boardId);
        if ($board && $board->parent_name == 'guilds' && !$authUser) {
            $this->Flash->error(__('書込みできません。'));
            $this->redirect($redirect);
            return;
        }
        
        // スレッドの作成
        $threadsTable = TableRegistry::get('Threads');
        $newThread = $threadsTable->newEntity([
            'name' => $threadName,
            'created' => $created,
            'modified' => $created,
            'board_id' => $boardId,
        ]);

        if ($newThread->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newThread->toString());
            return $this->redirect($redirect);
        }
        
        if ($threadsTable->save($newThread)) {
            Log::write('debug', $newThread->toString());
        } else {
            $this->Flash->error(__('登録に失敗しました。'));
            Log::write('error', $newThread->toString());
            return $this->redirect($redirect);
        }

        // ポストの作成
        $postsTable = TableRegistry::get('Posts');
        $newPost = $postsTable->newEntity([
            'name' => $postName,
            'content' => $postContent,
            'created' => $created,
            'modified' => $created,
            'thread_id' => $newThread->id,
        ]);
        
        if ($newPost->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newPost->toString());
            $threadsTable->delete($newThread);
            return $this->redirect($redirect);
        }
        
        if ($postsTable->save($newPost)) {
            Log::write('debug', $newPost->toString());
        } else {
            $this->Flash->error(__('登録に失敗しました。'));
            Log::write('error', $newPost->toString());
            $threadsTable->delete($newThread);
        }

        return $this->redirect($redirect);
    }
}

?>

