<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Log\Log;
use App\Model\Table;
use Cake\ORM\TableRegistry;
use DateTime;

class BoardsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->viewBuilder()->layout('fwu-default');
	}

	public function index()
	{
		$boards = $this->Boards->find('all');
		$this->set('boards', $boards);
	}

	public function view($boardId)
	{
		$this->loadModel('Boards');
		$this->loadModel('Threads');

		$board = $this->Boards->find()
			->where(['id' => $boardId])
			->first()
			;

		$threads = $this->Threads->find('all')
			->where(['board_id' => $boardId])
			->order(['name' => 'DESC'])
			;

		$this->set('board', $board);
		$this->set('threads', $threads);
	}

	public function post()
	{
		$threadName = $this->request->data('threadName');
		$postName = $this->request->data('postName');
		$postContent = $this->request->data('postContent');
		$created = new DateTime(date('Y-m-d H:i:s'));
		$boardId = $this->request->data('boardId');
		$redirect = ['action' => 'board', $boardId];
		
		// スレッドの作成
		$threadsTable = TableRegistry::get('Threads');
		$newThread = $threadsTable->newEntity([
			'name' => $threadName,
			'created' => $created,
			'board_id' => $boardId,
		]);
		
		if ($threadsTable->save($newThread)) {
			Log::write('debug', $newThread->toString());
		} else {
			$this->Flash->error('入力が不正です。');
			Log::write('error', $newThread->toString());
			$this->redirect($redirect);
			return;
		}

		// ポストの作成
		$postsTable = TableRegistry::get('Posts');
		$newPost = $postsTable->newEntity([
			'name' => $postName,
			'content' => $postContent,
			'thread_id' => $newThread->id,
		]);
		
		if ($postsTable->save($newPost)) {
			Log::write('debug', $newPost->toString());
		} else {
			$this->Flash->error('入力が不正です。');
			Log::write('error', $newPost->toString());
			$threadsTable->delete($newThread);
		}

		$this->redirect($redirect);
	}
}

?>

