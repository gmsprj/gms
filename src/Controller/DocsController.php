<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Docs Controller
 *
 * ドキュメントを管理するアプリケーション。
 *
 * @property \App\Model\Table\GuildsTable $Guilds
 */
class DocsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->set('docs', $this->Docs->find()->all());
        $this->set('_serialize', ['docs']);
    }

    /**
     * View method
     *
     * @param string|null $id Docs id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $doc = $this->Docs->get($id);
        if (!$doc) {
            throw new NotFoundException(__('Not found docs of ' + $id));
        }

        $this->set('doc', $doc);
        $this->set('_serialize', ['doc']);
    }
}

