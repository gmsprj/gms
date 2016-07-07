<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * News Controller
 *
 * ニュースを管理するアプリケーション。
 *
 * @property \App\Model\Table\NewsTable $News
 */
class NewsController extends AppController
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
            $select = [
                'id' => 'L.id',
                'content' => 'L.content',
                'created' => 'L.created',
                'sourceId' => 'R.id',
            ];

            $where = [];
            if ($ownerId) {
                $where['R.id'] = $ownerId;
            }

            $news = $this->Cells->findCells('texts', 'news', $owners)
                ->select($select)
                ->where($where);
        } else {
            $news = $this->Cells->findAllTextsNews();
        }

        if ($limit) {
            $news->limit($limit);
        }

        $news->all();

        $this->set('news', $news);
        $this->set('_serialize', [
            'news',
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Guild id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    }
}

