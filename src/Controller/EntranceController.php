<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;

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

