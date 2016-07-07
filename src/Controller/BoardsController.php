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
 * Boards Controller
 *
 * 板を管理するアプリケーション。
 *
 * @see src/Controller/Threads.php
 * @see src/Controller/Posts.php
 *
 * @property \App\Model\Table\BoardsTable $Boards
 */
class BoardsController extends AppController
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
        $this->Auth->allow([]);
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $query = &$this->request->query;

        $owners = (isset($query['owners']) ? $query['owners'] : null);
        $ownerId = (isset($query['ownerId']) ? $query['ownerId'] : null);
        $limit = (isset($query['limit']) ? $query['limit'] : null);

        if ($owners) {
            $q = $this->Cells->findCells('boards', 'owners', $owners);

            $select = [
                'id' => 'L.id',
                'name' => 'L.name',
                'description' => 'L.description',
                'created' => 'L.created',
                'modified' => 'L.modified',
                'ownerId' => 'R.id',
                'ownerName' => 'R.name',
            ];
            $q->select($select);

            $where = [];
            if ($ownerId) {
                $where['R.id'] = $ownerId;
            }
            $q->where($where);

        } else {
            $q = $this->Boards->find();
        }

        if ($limit) {
            $q->limit($limit);
        }

        $q->all();

        $this->set('boards', $q);
        $this->set('_serialize', [
            'boards',
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Boards id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $board = $this->Boards->get($id);
        $owner = $this->Cells->find()
            ->select([
                'name' => 'Cells.name',
            ])->where([
                'Cells.name LIKE' => '%boards-owners-%',
                'Cells.left_id' => $id,
            ])->first();
        $board['owners'] = substr($owner['name'], strrpos($owner['name'], '-')+1);

        $this->set('board', $board);
        $this->set('_serialize', [
            'board',
        ]);
    }
}

?>

