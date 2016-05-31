<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Log\Log;
use App\Model\Table;
use \Exception;
use Cake\ORM\TableRegistry;
use DateTime;

class ThreadsController extends AppController
{
	public function index()
	{
		$this->autoRender = false;
		throw new Exception('そんなページありません。');
	}

	public function thread($threadId)
	{
		$this->viewBuilder()->layout('fwu-default');
		
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
			Log::write('error', $newPost->toString());
		}
		
		$this->redirect(['action' => 'thread', $threadId]);
	}
}

?>

