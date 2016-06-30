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
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $customDocs = $this->Docs->find()
            ->hydrate(false)
            ->join([
                'table' => 'guilds',
                'alias' => 'A',
                'type' => 'INNER',
                'conditions' => 'A.id = Docs.guild_id'
            ])->select([
                'guildId' => 'A.id',
                'guildName' => 'A.name',
                'docId' => 'Docs.id',
                'docName' => 'Docs.name'
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
        $guild = $this->Guilds->get($doc->guild_id);

        $this->set('doc', $doc);
        $this->set('guild', $guild);
        $this->set('_serialize', [
            'doc',
            'guild'
        ]);
    }
}

