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
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['index', 'view']);
        $this->loadModel('Threads');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        throw new NotFoundException(__('見つかりません。'));
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
        // 板
        $board = $this->Boards->find()
            ->where(['id' => $id])
            ->first();
        if (!$board) {
            throw new NotFoundException(__('板が見つかりません。'));
        }

        // スレッドのリスト
        $threads = $this->Threads->find('all')
            ->where(['board_id' => $id]);

        // 認証ユーザーから投稿者ネームを得る
        // TODO: 板毎にデフォルトの「名無しさん」等が必要になった場合はここを変更
        $authUser = $this->Auth->user();
        $postName = $authUser ? $authUser['name'] : __('名無しさん');

        // テンプレートに設定
        $this->set('board', $board);
        $this->set('threads', $threads);
        $this->set('postName', $postName);
        $this->set('_serialize', ['board', 'threads', 'postName']);
    }
}

?>

