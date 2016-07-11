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
        $this->loadModel('Threads');
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
            $q = $this->Posts->find();

            $where = [];
            if ($ownerId) {
               $where['thread_id'] = $ownerId; 
            }
            $q->where($where);

        } else {
            $q = $this->Posts->find();
        }

        if ($limit) {
            $q->limit($limit);
        }

        $q->all();

        $this->set('posts', $q);
        $this->set('_serialize', [
            'posts',
        ]);
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
            Log::error('Invalid method of ' . $this->request->method());
            return $this->redirect(['controller' => 'Guilds', 'action' => 'index']);
        }

        // パラメーターを取得
        $authUser = $this->Auth->user();
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $userId = $this->request->data('userId');
        $threadId = $this->request->data('threadId');
        $thread = $this->Threads->get($threadId);
        $redirect = ['controller' => 'Threads', 'action' => 'view', $threadId];

        // ポストの作成
        $postsTable = TableRegistry::get('Posts');
        $newPost = $postsTable->newEntity([
            'name' => $name,
            'content' => $content,
            'user_id' => $userId,
            'thread_id' => $threadId,
        ]);
        
        if ($newPost->errors()) {
            $this->Flash->error(__('Invalid input data'));
            Log::error(json_encode($newPost->errors()));
            return $this->redirect($redirect);
        }

        if (!$postsTable->save($newPost)) {
            $this->Flash->error(__('Failed to save'));
            Log::error(json_encode($newPost->errors()));
        }
        
        return $this->redirect($redirect);
    }
}

?>

