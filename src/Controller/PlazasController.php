<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use DateTime;
use Exception;

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
    }

    public function index()
    {
        // Plazas の板のリスト
        $boards = $this->Boards->find('all')
            ->where(['parent_name' => 'plazas'])
            ->order(['name' => 'DESC']);

        if ($boards->count() == 0) {
            throw new Exception('板がありません。');
        }
        
        // 表示板
        $dispBoard = $this->Boards->find()
            ->where(['name' => 'ロビー', 'parent_name' => 'plazas'])
            ->first();

        if ($dispBoard == null) {
            throw new Exception('ロビー板を作成してください。');
        }
        
        // 表示板スレッドリスト
        $dispThreads = $this->Threads->find('all')
            ->where(['board_id' => $dispBoard->id]);

        if ($dispThreads->count() == 0) {
            throw new Exception($dispBoard->name . '板にスレッドがありません。最低１スレッド必要です。');
        }
        
        // 表示スレッド
        $dispThread = $this->Threads->find('all')
            ->where(['board_id' => $dispBoard->id])
            ->first();
        
        // 表示スレッドのポストリスト
        $dispPosts = $this->Posts->find('all')
            ->where(['thread_id' => $dispThread->id]);


        // ギルドのリスト
        $dispGuilds = $this->Guilds->find('all');

        // 認証ユーザーを取得して投稿者ネームを得る
        $authUser = $this->Auth->user();
        $postName = ($authUser == null ? '名無しさん' : $authUser['name']);

        // テンプレートに設定
        $this->set('boards', $boards);
        $this->set('dispBoard', $dispBoard);
        $this->set('dispThreads', $dispThreads);
        $this->set('dispThread', $dispThread);
        $this->set('dispPosts', $dispPosts);
        $this->set('dispGuilds', $dispGuilds);
        $this->set('postName', $postName);
    }

    /* TODO: ThreadsController.php: post() と重複 */
    public function post()
    {
        $name = $this->request->data('name');
        $content = $this->request->data('content');
        $threadId = $this->request->data('threadId');
        $created = new DateTime(date('Y-m-d H:i:s'));

        // ポストの作成
        $postTable = TableRegistry::get('Posts');
        $newPost = $postTable->newEntity([
            'name' => $name,
            'content' => $content,
            'thread_id' => $threadId,
        ]);
        
        if ($postTable->save($newPost)) {
            Log::write('debug', $newPost->toString());
        } else {
            $this->Flash->error('入力が不正です。');
            Log::write('error', $newPost->toString());
        }
        
        $this->redirect(['action' => 'index']);
    }
}

?>

