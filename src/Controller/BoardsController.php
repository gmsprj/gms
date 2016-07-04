<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use DateTime;

/**
 * Boards Controller
 *
 * 板を管理するアプリケーション。
 *
 * @see src/Controller/Threads.php
 * @see src/Controller/Posts.php
 *
 * @property \App\Model\Table\BoardsTable $Boards
 */
class BoardsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
        $this->Auth->allow([]);
        $this->loadModel('Threads');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $boards = $this->Boards->find()
            ->all();
        $this->set('boards', $boards);
        $this->set('_serialize', [
            'boards',
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Boards id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $board = $this->Boards->get($id);
        $threads = $this->Threads->find()
            ->where([
                'board_id' => $id
            ])->all();
        $postName = ($user ? $user['name'] : __('名無しさん'));

        $this->set('user', $user);
        $this->set('board', $board);
        $this->set('threads', $threads);
        $this->set('postName', $postName);
        $this->set('csrf', $this->Csrf->request->_csrfToken);
        $this->set('_serialize', [
            'user',
            'board',
            'threads',
            'postName',
            'csrf',
        ]);
    }
}

?>

