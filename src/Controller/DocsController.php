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
        $this->Auth->allow(['add']);
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

        // Name and Description

        $nd = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'K',
                'type' => 'INNER',
                'conditions' => 'K.id = Cells.left_id'
            ])->join([
                'table' => 'texts',
                'alias' => 'V',
                'type' => 'INNER',
                'conditions' => 'V.id = Cells.right_id'
            ])->select([
                'key' => 'K.content',
                'value' => 'V.content',
            ])->where([
                'Cells.name LIKE' => '%-kv-%',
                'K.content' => '文書について'
            ])->first();

        // Set

        $this->set('nd', $nd);
        $this->set('customDocs', $customDocs);
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
            'nd',
            'customDocs',
            'csrf',
        ]);
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

        $this->set('doc', $doc);
        $this->set('_serialize', [
            'doc',
        ]);
    }

    /**
     * Add method
     */
    public function add()
    {
        $failTo = ['controller' => 'Docs', 'action' => 'index'];
        $doneTo = ['controller' => 'Docs', 'action' => 'index'];

        // Guilds

        $guildId = $this->request->data('guildId'); // doc-owner-guild の guilds.id
        if (!TableRegistry::get('Guilds')->exists(['id' => $guildId])) {
            $this->Flash->error(__('Not found guild id'));
            Log::write('error', 'Not found guild id ' + $guildId);
            return $this->redirect($failTo);
        }
        
        // Docs

        $docName = $this->request->data('docName');
        $docContent = $this->request->data('docContent');
        $docState = $this->request->data('docState');

        $tab = TableRegistry::get('Docs');
        $doc = $tab->newEntity([
            'name' => $docName,
            'content' => $docContent,
            'state' => $docState,
        ]);

        if ($doc->errors()) {
            $this->Flash->error(__('Invalid input data'));
            Log::write('error', json_encode($doc->errors()));
            return $this->redirect($failTo);
        }

        if (!$tab->save($doc)) {
            $this->Flash->error(__('Failed to save'));
            Log::write('error', json_encode($doc->errors()));
            return $this->redirect($failTo);
        }

        // Cells for doc-owner-guild

        $tab = TableRegistry::get('Cells');
        $cell = $tab->newEntity([
            'name' => 'doc-owner-guild',
            'left_id' => $doc->id,
            'right_id' => $guildId,
        ]);

        if (!$tab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            return $this->redirect($failTo);
        }

        return $this->redirect($doneTo);
    }
}

