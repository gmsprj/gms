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
 * Posts Controller
 *
 * ポストを管理するアプリケーション。
 *
 * @see src/Controller/Boards.php
 * @see src/Controller/Threads.php
 */
class PostsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['index', 'view', 'add']);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        return new NotFoundException(__('見つかりません。'));
    }

    /**
     * View method
     *
     * @param string|null $id Posts id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        return new NotFoundException(__('見つかりません。'));
    }

    /**
     * Add method
     *
     * ポストを追加する。
     * 追加に成功したら threads/view/threadId にリダイレクト。
     * 失敗したら guilds/index にリダイレクト。
     *
     * @param string request->data('name') 
     * @param string request->data('content') 
     * @param string request->data('threadId') 
     */
    public function add()
    {
        // メソッド名をチェック
        if (!$this->request->is('post')) {
            Log::write('error', 'Invalid method of ' . $this->request->method());
            return $this->redirect(['controller' => 'Guilds', 'action' => 'index']);
        }

        // パラメーターを取得
        $authUser = $this->Auth->user();
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $threadId = $this->request->data('threadId');
        $thread = $this->Threads->get($threadId);
        $created = new DateTime(date('Y-m-d H:i:s'));
        $redirect = ['controller' => 'Threads', 'action' => 'view', $threadId];

        // 板の親が guilds でかつ、認証ユーザーがサインイン・ユーザーでないなら書き込み不可
        $board = $this->Boards->get($thread->board_id);
        if ($board && $board->parent_name == 'guilds' && !$authUser) {
            $this->Flash->error(__('書込みできません。'));
            return $this->redirect($redirect);
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
            return $this->redirect($redirect);
        }

        if (!$postsTable->save($newPost)) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newPost->toString());
        }
        
        return $this->redirect($redirect);
    }
}

?>

