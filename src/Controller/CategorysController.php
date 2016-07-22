<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

class CategorysController extends AppController
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
        $this->Auth->allow([
            'entry',
            'leave',
        ]);
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if ($this->request->is('get')) {
            $guilds = $this->Categorys->find()->all();
            $this->set('guilds', $guilds);
            $this->set('_serialize', [
                'guilds',
            ]);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($this->request->is('get')) {
            $guild = $this->Categorys->get($id);
            $guild['images'] = $this->Cells->findCells('images', 'syms', 'guilds')
                ->select([
                    'id' => 'L.id',
                    'url' => 'L.url',
                ])->where([
                    'R.id' => $id,
                ])->all();

            $this->set('guild', $guild);
            $this->set('_serialize', [
                'guild',
            ]);
        }
    }
}

