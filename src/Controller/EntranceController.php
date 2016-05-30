<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Log\Log;
use App\Model\Table;

class EntranceController extends AppController
{
	public function index()
	{
		$this->viewBuilder()->layout('fwu-entrance');
		$this->set('title', 'FreeWorkerUnion（仮）');
	}
}

?>

