<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use DateTime;

/**
 * Plazas Controller
 *
 * 広場を管理するアプリケーション。
 */
class PlazasController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['index', 'post']);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Posts');
        $this->loadModel('Guilds');
        $this->loadModel('Plazas');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        // Plaza
        $plaza = $this->Plazas->find()->first();
        if (!$plaza) {
            throw new NotFoundException(__('広場がありません。最低１つ必要です。'));
        }

        // Plazas の板のリスト
        $boards = $this->Boards->find('all')
            ->where(['parent_name' => 'plazas', 'parent_id' => $plaza->id]);
        if ($boards->count() == 0) {
            throw new NotFoundException(__('板がありません。'));
        }
        
        // 表示板
        $board = $this->Boards->find()
            ->where(['name' => 'ロビー', 'parent_name' => 'plazas'])
            ->first();
        if (!$board) {
            throw new NotFoundException(__('ロビー板を作成してください。'));
        }
        
        // 表示板スレッドリスト
        $threads = $this->Threads->find('all')
            ->where(['board_id' => $board->id]);
        if ($threads->count() == 0) {
            throw new NotFoundException($board->name . __('板にスレッドがありません。最低１つ必要です。'));
        }
        
        // 表示スレッド
        $thread = $this->Threads->find('all')
            ->where(['board_id' => $board->id])
            ->first();
        
        // 表示スレッドのポストリスト
        $posts = $this->Posts->find('all')
            ->where(['thread_id' => $thread->id]);

        // ギルドのリスト
        $guilds = $this->Guilds->find('all');

        // 認証ユーザーを取得して投稿者ネームを得る
        // TODO: 板毎にデフォルトの「名無しさん」等が必要になった場合はここを変更
        $authUser = $this->Auth->user();
        $postName = $authUser ? $authUser['name'] : __('名無しさん');

        // テンプレートに設定
        $this->set('plaza', $plaza);
        $this->set('board', $board);
        $this->set('boards', $boards);
        $this->set('thread', $thread);
        $this->set('threads', $threads);
        $this->set('posts', $posts);
        $this->set('guilds', $guilds);
        $this->set('postName', $postName);
    }
}

?>

