<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use App\Model\Table;
use Cake\ORM\TableRegistry;
use DateTime;

class PlazaController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->viewBuilder()->layout('fwu-default');
	}

	public function index()
	{
		$this->loadModel('Boards');
		$this->loadModel('Threads');
		$this->loadModel('Posts');
		
		// 板のリスト
		$boards = $this->Boards->find('all')
			->order(['name' => 'DESC'])
			;
		
		// 表示板
		$dispBoard = $this->Boards->find()
			->where(['name' => 'ロビー'])
			->first()
			;
		
		// 表示板スレッドリスト
		$dispThreads = $this->Threads->find('all')
			->where(['board_id' => $dispBoard->id])
			;
		
		// 表示スレッド
		$dispThread = $this->Threads->find('all')
			->where(['board_id' => $dispBoard->id])
			->first()
			;
		
		// 表示スレッドのポストリスト
		$dispPosts = $this->Posts->find('all')
			->where(['thread_id' => $dispThread->id])
			;

		// テンプレートに設定
		$this->set('boards', $boards);
		$this->set('dispBoard', $dispBoard);
		$this->set('dispThreads', $dispThreads);
		$this->set('dispThread', $dispThread);
		$this->set('dispPosts', $dispPosts);
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
			Log::write('error', $newPost->toString());
		}
		
		$this->redirect(['action' => 'index']);
	}
}

?>

