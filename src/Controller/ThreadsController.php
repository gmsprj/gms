<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Exception;
use DateTime;

class ThreadsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['view', 'post']);
    }

    public function index()
    {
        $this->autoRender = false;
        throw new Exception(__('そんなページありません。'));
    }

    public function view($threadId)
    {
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Posts');

        $thread = $this->Threads->find()
            ->where(['id' => $threadId])
            ->first();

        if ($thread == null) {
            throw new Exception(__('そんなスレッドありません。'));
        }
        
        $board = $this->Boards->find()
            ->where(['id' => $thread->board_id])
            ->first();

        $posts = $this->Posts->find('all')
            ->where(['thread_id' => $thread->id]);

        $this->set('board', $board);
        $this->set('thread', $thread);
        $this->set('posts', $posts);
    }

    /* TODO: PlazaController.php: post() と重複 */
    public function post()
    {
        if (!$this->request->is('post')) {
            return;
        }

        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $threadId = $this->request->data('threadId');
        $created = new DateTime(date('Y-m-d H:i:s'));
        $redirect = ['action' => 'view', $threadId];

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

