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
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->set('sites', $this->Sites->find('all'));
        $this->set('_serialize', ['sites']);
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
        $this->set('site', $this->Sites->get($id));
        $this->set('_serialize', ['site']);
    }
}
