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
        $this->Auth->allow(['add', 'edit', 'update']);// TODO: デバッグ用の allow
        $this->loadModel('Guilds');
        $this->loadModel('Posts');
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
                'Cells.name' => 'docs-owners-guilds'
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
                'Cells.name LIKE' => '%-kvs-%',
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
        $customDoc = $this->Cells->find()
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
                'docId' => 'D.id',
                'docName' => 'D.name',
                'docContent' => 'D.content',
                'docState' => 'D.state',
                'docCreated' => 'D.created',
                'docModified' => 'D.modified',
                'guildId' => 'G.id',
                'guildName' => 'G.name',
            ])->where([
                'Cells.name' => 'docs-owners-guilds',
                'D.id' => $id,
            ])->first();
        $thread = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'threads',
                'alias' => 'T',
                'type' => 'INNER',
                'conditions' => 'T.id = Cells.left_id'
            ])->select([
                'id' => 'T.id',
                'name' => 'T.name',
            ])->where([
                'Cells.name' => 'threads-refs-docs',
                'Cells.right_id' => $id,
            ])->first();
        $posts = $this->Posts->find()
            ->where([
                'thread_id' => $thread['id'],
            ])->all();

        $this->set('thread', $thread);
        $this->set('posts', $posts);
        $this->set('customDoc', $customDoc);
        $this->set('_serialize', [
            'thread',
            'posts',
            'customDoc',
        ]);
    }

    /**
     * Add method
     */
    public function add()
    {
        $failTo = ['controller' => 'Docs', 'action' => 'index'];
        $doneTo = ['controller' => 'Docs', 'action' => 'index'];

        // Users
        /*
        $user = $this->Auth->user();
        if (!$user) {
            $this->Flash->error(__('文書の提案にはサインインが必要です。'));
            return $this->redirect($failTo);
        }*/

        // Guilds

        $guildId = $this->request->data('guildId'); // doc-owner-guild の guilds.id
        $guild = $this->Guilds->get($guildId);
        if (!$guild) {
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

        // Cells for thread-ref-doc 
        
        $threadId = $this->request->data('threadId'); // thread-ref-doc の threads.id
        $tab = TableRegistry::get('Cells');
        $cell = $tab->newEntity([
            'name' => 'threads-refs-docs',
            'left_id' => $threadId,
            'right_id' => $doc->id,
        ]);

        if (!$tab->save($cell)) {
            $this->Flash->error(__('Failed to save'));
            Log::write('error', json_encode($cell->errors()));
            return $this->redirect($failTo);
        }

        // Cells for doc-owner-guild

        $tab = TableRegistry::get('Cells');
        $cell = $tab->newEntity([
            'name' => 'docs-owners-guilds',
            'left_id' => $doc->id,
            'right_id' => $guildId,
        ]);

        if (!$tab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            return $this->redirect($failTo);
        }

        // text-news-guild

        $this->addNews([
            'id' => $guildId,
            'name' => 'guilds',
            'content' => sprintf('%sで「%s」が提案されました。', $guild->name, $docName)
        ]);

        return $this->redirect($doneTo);
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $doc = $this->Docs->get($id);

        $thread = $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'threads',
                'alias' => 'T',
                'type' => 'INNER',
                'conditions' => 'T.id = Cells.left_id'
            ])->select([
                'id' => 'T.id',
                'name' => 'T.name',
            ])->where([
                'Cells.name' => 'threads-refs-docs',
                'Cells.right_id' => $id,
            ])->first();

        $this->set('doc', $doc);
        $this->set('thread', $thread);
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
            'doc',
            'thread',
            'csrf',
        ]);
    }

    public function update()
    {
        $id = $this->request->data('id');
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $threadId = $this->request->data('threadId');
        $doneTo = ['controller' => 'Docs', 'action' => 'view', $id];

        $doc = $this->Docs->get($id);
        $oldName = $doc->name;
        $doc->name = $name;
        $doc->content = $content;
        
        $tab = TableRegistry::get('Docs');
        $tab->save($doc);

        $this->addNews([
            'id' => $id,
            'name' => 'docs',
            'content' => sprintf('文書「%s」が更新されました。', $oldName)
        ]);

        $this->redirect($doneTo);
    }
}

