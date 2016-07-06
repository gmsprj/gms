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
        $this->loadModel('Cells');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $owners = $this->request->query('owners');
        if ($owners) {
            $boards = $this->Cells->findCells('boards', 'owners', $owners)
                ->select([
                    'id' => 'L.id',
                    'name' => 'L.name',
                    'ownerId' => 'R.id',
                    'ownerName' => 'R.name',
                ])->all();
        } else {
            $boards = $this->Boards->find()->all();
        }

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
        $board = $this->Boards->get($id);

        $this->set('board', $board);
        $this->set('_serialize', [
            'board',
        ]);
    }
}

?>

