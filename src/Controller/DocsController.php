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
 * @property \App\Model\Table\DocsTable $Docs
 */
class DocsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
        $this->loadModel('Guilds');
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $customDocs = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'docs',
                'alias' => 'D',
                'type' => 'INNER',
                'conditions' => 'D.id = Cells.left_id'
            ])->join([
                'table' => 'guilds',
                'alias' => 'G',
                'type' => 'INNER',
                'conditions' => 'G.id = Cells.right_id'
            ])->select([
                'guildId' => 'G.id',
                'guildName' => 'G.name',
                'docId' => 'D.id',
                'docName' => 'D.name'
            ])->where([
                'Cells.name' => 'doc-owner-guild'
            ])->all();

        $this->set('customDocs', $customDocs);
        $this->set('_serialize', ['customDocs']);
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
        $guild = $this->Guilds->get($doc->id);//TODO

        $this->set('doc', $doc);
        $this->set('guild', $guild);
        $this->set('_serialize', [
            'doc',
            'guild'
        ]);
    }
}

