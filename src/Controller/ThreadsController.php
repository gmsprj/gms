<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Exception;
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
        $this->Auth->allow(['index', 'view', 'post']);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Posts');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['view', 'post']);
    }

    public function index()
    {
        $threads = $this->Threads->find('all');

        $this->set('user', $this->Auth->user());
        $this->set('threads', $threads);
    }

    public function view($threadId)
    {

        $thread = $this->Threads->find()
            ->where(['id' => $threadId])
            ->first();

        $threads = $this->Threads->find('all')
            ->where(['board_id' => $thread->board_id]);

        if (!$thread) {
            throw new Exception(__('そんなスレッドありません。'));
        }
        
        $board = $this->Boards->find()
            ->where(['id' => $thread->board_id])
            ->first();

        $posts = $this->Posts->find('all')
            ->where(['thread_id' => $thread->id]);

        $authUser = $this->Auth->user();
        $postName = ($authUser ? $authUser['name'] : '名無しさん');

        // テンプレートに設定
        $this->set('board', $board);
        $this->set('thread', $thread);
        $this->set('threads', $threads);
        $this->set('posts', $posts);
        $this->set('postName', $postName);
    }

    /* TODO: PlazasController.php: post() と重複 */
    public function post()
    {
        if (!$this->request->is('post')) {
            return;
        }

        $authUser = $this->Auth->user();
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $threadId = $this->request->data('threadId');
        $thread = $this->Threads->get($threadId);
        $created = new DateTime(date('Y-m-d H:i:s'));
        $redirect = ['action' => 'view', $threadId];

        // 板の親が guilds でかつ、認証ユーザーがサインイン・ユーザーでないなら書き込み不可
        $board = $this->Boards->get($thread->board_id);
        if ($board && $board->parent_name == 'guilds') {
            if (!$authUser) {
                $this->Flash->error(__('書込みできません。'));
                $this->redirect($redirect);
                return;
            }
        }

        // ポストの作成
        $postsTable = TableRegistry::get('Posts');
        $newPost = $postsTable->newEntity([
            'name' => $name,
            'content' => $content,
            'thread_id' => $threadId,
        ]);
        
        if ($newPost->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newPost->toString());
            $this->redirect($redirect);
            return;
        }

        if ($postsTable->save($newPost)) {
            Log::write('debug', $newPost->toString());
        } else {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newPost->toString());
        }
        
        $this->redirect($redirect);
    }
}

?>

