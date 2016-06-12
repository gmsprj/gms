<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;

/**
 * Entrances Controller
 *
 * Web サイトの入り口を管理するアプリケーション。
 *
 * @see config/routes.php
 * @see src/Template/Entrances/index.ctp
 * @see src/Template/Layout/fwu-entrances.ctp
 */
class EntrancesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->layout('fwu-entrances');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    }
}

?>

