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

        $this->set('customDocs', $customDocs);
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
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
        $guild = $this->Guilds->get(1);//TODO: Docs のギルドを Cells から検索

        $this->set('doc', $doc);
        $this->set('guild', $guild);
        $this->set('_serialize', [
            'doc',
            'guild'
        ]);
    }

    /**
     * Add method
     */
    public function add()
    {
        $failTo = ['controller' => 'Docs', 'action' => 'index'];
        $doneTo = ['controller' => 'Docs', 'action' => 'index'];

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

        // Cells

        $tab = TableRegistry::get('Cells');
        $cell = $tab->newEntity([
            'name' => 'doc-owner-guild',
            'left_id' => $doc->id,
            'right_id' => 1,
        ]);

        if (!$tab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            return $this->redirect($failTo);
        }

        return $this->redirect($doneTo);
    }
}

