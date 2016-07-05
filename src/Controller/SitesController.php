<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Sites Controller
 *
 * サイトを管理するアプリケーション。
 *
 * @property \App\Model\Table\SitesTable $Sites
 */
class SitesController extends AppController
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
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $sites = $this->Sites->find()->all();

        $this->set('_serialize', [
            'sites', $sites,
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Site id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        Log::debug('----');
        Log::debug($this->request->header('Accept'));
        Log::debug('----');
        $site = $this->Sites->get($id);
        $this->set('site', $site);
        $this->set('_serialize', [
            'site',
        ]);
    }
}
