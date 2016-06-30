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
        $this->viewBuilder()->layout('gm-default');
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
        return new NotFoundException(__('Not found'));
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
        return new NotFoundException(__('Not found'));
    }

    /**
     * Add method
     *
     * @param string request->data('name') post name
     * @param string request->data('content') post content
     * @param string request->data('threadId') id of thread for post
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
            $this->Flash->error(__('Can\'t write'));
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
            $this->Flash->error(__('Invalid input data'));
            return $this->redirect($redirect);
        }

        if (!$postsTable->save($newPost)) {
            $this->Flash->error(__('Invalid input data'));
        }
        
        return $this->redirect($redirect);
    }
}

?>

