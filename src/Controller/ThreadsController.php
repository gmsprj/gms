<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;
use DateTime;

class ThreadsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->viewBuilder()->layout('fwu-default');
	}

	public function index()
	{
		$this->autoRender = false;
		throw new Exception('そんなページありません。');
	}

	public function view($threadId)
	{
		$this->loadModel('Boards');
		$this->loadModel('Threads');
		$this->loadModel('Posts');

		$thread = $this->Threads->find()
			->where(['id' => $threadId])
			->first()
			;

		if ($thread == null) {
			throw new Exception('そんなスレッドありません。');
		}
		
		$board = $this->Boards->find()
			->where(['id' => $thread->board_id])
			->first()
			;

		$posts = $this->Posts->find('all')
			->where(['thread_id' => $thread->id])
			;

		$this->set('board', $board);
		$this->set('thread', $thread);
		$this->set('posts', $posts);
	}

	/* TODO: PlazaController.php: post() と重複 */
	public function post()
	{
		$name = $this->request->data('name');
		$content = $this->request->data('content');
		$threadId = $this->request->data('threadId');
		$created = new DateTime(date('Y-m-d H:i:s'));

		// ポストの作成
		$postsTable = TableRegistry::get('Posts');
		$newPost = $postsTable->newEntity([
			'name' => $name,
			'content' => $content,
			'thread_id' => $threadId,
		]);
		
		if ($postsTable->save($newPost)) {
			Log::write('debug', $newPost->toString());
		} else {
			$this->Flash->error('入力が不正です。');
			Log::write('error', $newPost->toString());
		}
		
		$this->redirect(['action' => 'thread', $threadId]);
	}
}

?>

