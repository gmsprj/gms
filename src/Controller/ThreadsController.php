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
 * Threads Controller
 *
 * スレッドを管理するアプリケーション。
 *
 * @see src/Controller/Boards.php
 * @see src/Controller/Posts.php
 */
class ThreadsController extends AppController
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
        $this->Auth->allow(['index', 'view', 'add']);
        $this->loadModel('Boards');
        $this->loadModel('Posts');
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $query = $this->request->query;

        $owners = (isset($query['owners']) ? $query['owners'] : null);
        $ownerId = (isset($query['ownerId']) ? $query['ownerId'] : null);
        $limit = (isset($query['limit']) ? $query['limit'] : null);
        
        if ($owners) {
            $q = $this->Threads->find();
            
            $where = [];
            if ($ownerId) {
               $where['board_id'] = $ownerId; 
            }
            $q->where($where);

        } else {
            $q = $this->Threads->find();
        }

        if ($limit) {
            $q->limit($limit);
        }

        $q->all();

        $this->set('threads', $q);
        $this->set('_serialize', [
            'threads',
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Threads id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $q = $this->Threads->get($id);
        $this->set('thread', $q);
        $this->set('_serialize', [
            'thread',
        ]);
    }

    /**
     * Add method
     *
     * 新規スレッドの作成。
     *
     * @param string request->data('threadName') 新規スレッド名
     * @param string request->data('postName') 新規スレッドのポストの投稿者名
     * @param string request->data('postContent') 新規スレッドのポストの内容
     * @param string request->data('boardId') 新規スレッドが属する板のID
     */
    public function add()
    {
        // メソッド名のチェック
        if (!$this->request->is('post')) {
            Log::error('Invalid method of ' . $this->request->method());
            return $this->redirect(['controller' => 'Guilds', 'action' => 'index']);
        }

        // パラメーターの取得
        $threadName = $this->request->data('threadName');
        $postName = $this->request->data('postName');
        $postContent = $this->request->data('postContent');
        $boardId = $this->request->data('boardId');

        $user = $this->Auth->user();

        $failTo = ['controller' => 'Boards', 'action' => 'view', $boardId];
        $doneTo = ['controller' => 'Boards', 'action' => 'view', $boardId];

        // スレッドの作成
        $threadsTable = TableRegistry::get('Threads');
        $newThread = $threadsTable->newEntity([
            'name' => $threadName,
            'board_id' => $boardId,
        ]);

        if ($newThread->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            return $this->redirect($failTo);
        }
        
        if (!$threadsTable->save($newThread)) {
            $this->Flash->error(__('登録に失敗しました。'));
            return $this->redirect($failTo);
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
            $threadsTable->delete($newThread);
            return $this->redirect($failTo);
        }
        
        if (!$postsTable->save($newPost)) {
            $this->Flash->error(__('登録に失敗しました。'));
            $threadsTable->delete($newThread);
        }

        // News
        $this->Cells->addTextsNews([
            'right' => 'boards',
            'rightId' => $boardId,
            'content' => sprintf('%sに新規スレッド「%s」が作成されました。', $board->name, $threadName),
        ]);

        return $this->redirect($doneTo);
    }
}

?>

