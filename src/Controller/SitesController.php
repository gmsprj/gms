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
            $sites = $this->Sites->find()->all();

            // sites に画像を紐付け
            foreach ($sites as $site) {
                $site['images'] = $this->Cells->findCells('images', 'syms', 'sites')
                    ->select([
                        'id' => 'L.id',
                        'url' => 'L.url',
                    ])->where([
                        'R.id' => $site['id'],
                    ])->all();
            }

            $this->set('sites', $sites);
            $this->set('_serialize', [
                'sites'
            ]);
        }
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
        if ($this->request->is('get')) {
            $site = $this->Sites->get($id);
            $images = $this->Cells->findCells('images', 'syms', 'sites')
                ->select([
                    'id' => 'L.id',
                    'url' => 'L.url',
                ])->where([
                    'R.id' => $id,
                ])->all();
            $site['images'] = $images;

            $this->set('site', $site);
            $this->set('_serialize', [
                'site',
            ]);
        }
    }
}
