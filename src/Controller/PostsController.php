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
        if (!$this->request->is('get')) {
            Log::error('Invalid request method of ' . $this->request->method());
            throw new NotfoundException(__('このメソッドには対応していません。'));
        }

        $query = $this->request->query;

        $owners = (isset($query['owners']) ? $query['owners'] : null);
        $ownerId = (isset($query['ownerId']) ? $query['ownerId'] : null);
        $limit = (isset($query['limit']) ? $query['limit'] : null);
        $order = (isset($query['order']) ? $query['order'] : null);
        $q = null;
        
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

        if ($order) {
            $q->order([
                $order => 'DESC',
            ]);
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
        $this->autoRender = false;

        // メソッド名をチェック
        if (!$this->request->is('post')) {
            Log::error('Invalid method of ' . $this->request->method());
            throw new NotfoundException(__('このメソッドには対応していません。'));
        }

        // パラメーターを取得
        //Log::debug(json_encode($this->request->data));
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $userId = $this->request->data('userId');
        $threadId = $this->request->data('threadId');
        if ($name == null || $content == null || $userId == null || $threadId == null) {
            throw new NotfoundException(__('不正なデータです。') . json_encode($this->request->data));
        }

        $thread = $this->Threads->get($threadId);

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
            throw new NotfoundException(__('不正なデータです。') . json_encode($this->request->data));
        }

        if (!$postsTable->save($newPost)) {
            $this->Flash->error(__('Failed to save'));
            Log::error(json_encode($newPost->errors()));
            throw new NotfoundException(__('応答に失敗しました。') . json_encode($this->request->data));
        }
        
        Log::debug('Success to POST of /posts');
        echo json_encode([
            'post' => $newPost,
        ]);
    }
}

?>

