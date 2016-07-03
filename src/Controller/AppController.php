<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth',[
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'signin'
            ],
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'signinRedirect',
            ],
            'logoutRedirect' => [
                'controller' => 'Guilds',
                'action' => 'index',
            ],
            'authError' => __('ログインできませんでした。ログインしてください。'),
        ]);
        $this->loadModel('Sites');
    }

    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index', 'view', 'display']);
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

        $this->set('site', $this->Sites->find()->first());
        $this->set('user', $this->Auth->user());
    }

    protected function addTextsNews($arr = [])
    {
        $textTab = TableRegistry::get('Texts');
        $cellsTab = TableRegistry::get('Cells');

        $text = $textTab->newEntity([
            'content' => __($arr['content']),
        ]);

        if (!$textTab->save($text)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($text->errors()));
            return false;
        }

        $cell = $cellsTab->newEntity([
            'name' => 'texts-news-' . $arr['right'],
            'left_id' => $text->id,
            'right_id' => $arr['rightId'],
        ]);

        if (!$cellsTab->save($cell)) {
            $this->Flash->error(__('Internal error'));
            Log::write('error', json_encode($cell->errors()));
            $textTab->delete($text);
            return false;
        }

        return true;
    }

    protected function findTextsNewsAll($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->select([
                'content' => 'L.content',
                'created' => 'L.created',
            ])->where([
                'Cells.name LIKE' => '%texts-news-%',
            ])->order([
                'L.created' => 'DESC',
            ]);
    }

    protected function findTextsNews($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->select([
                'content' => 'L.content',
                'created' => 'L.created',
            ])->where([
                'Cells.name' => 'texts-news-' . $arr['right'],
            ])->order([
                'L.created' => 'DESC',
            ]);
    }

    protected function findImagesSyms($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'images',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->select([
                'url' => 'L.url',
            ])->where([
                'Cells.name' => 'images-syms-' . $arr['right'],
            ]);
    }

    /**
     * @param $arr['right']
     * @param $arr['rightId']
     * @param $arr['state']
     */
    protected function findDocsOwners($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'docs',
                'alias' => 'D',
                'type' => 'INNER',
                'conditions' => 'D.id = Cells.left_id',
            ])->join([
                'table' => 'guilds',
                'alias' => 'G',
                'type' => 'INNER',
                'conditions' => 'G.id = Cells.right_id',
            ])->select([
                'id' => 'D.id',
                'name' => 'D.name',
            ])->where([
                'Cells.name' => 'docs-owners-' . $arr['right'],
                'G.id' => $arr['rightId'],
                'D.state' => $arr['state'],
            ]);
    }

    protected function findBoardsOwners($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'boards',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id',
            ])->join([
                'table' => $arr['right'],
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id',
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->where([
                'Cells.name' => 'boards-owners-' . $arr['right'],
                'R.id' => $arr['rightId'],
            ]);
    }

    protected function findKVSAll($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'texts',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->join([
                'table' => 'texts',
                'alias' => 'R',
                'type' => 'INNER',
                'conditions' => 'R.id = Cells.right_id'
            ])->select([
                'key' => 'L.content',
                'value' => 'R.content',
            ])->where([
                'Cells.name LIKE' => '%-kvs-%',
            ]);
    }

    protected function findThreadsRefs($arr = [])
    {
        return $this->Cells->find()
            ->hydrate(false)
            ->join([
                'table' => 'threads',
                'alias' => 'L',
                'type' => 'INNER',
                'conditions' => 'L.id = Cells.left_id'
            ])->select([
                'id' => 'L.id',
                'name' => 'L.name',
            ])->where([
                'Cells.name' => 'threads-refs-' . $arr['right'],
                'Cells.right_id' => $arr['rightId'],
            ]);
    }
}
