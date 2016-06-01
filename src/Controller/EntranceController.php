<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Log\Log;
use App\Model\Table;

class EntranceController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->viewBuilder()->layout('fwu-entrance');
	}

	public function index()
	{
	}
}

?>

