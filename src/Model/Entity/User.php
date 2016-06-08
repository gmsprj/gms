<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Log\Log;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $guild_id
 * @property \App\Model\Entity\Guild $guild
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * 引数のパスワードをハッシュ化して返す。
     * ハッシュで使われる salt は config/app.php: Security/salt を参照。
     *
     * @param string $password パスワード文字列
     * @return string ハッシュ化されたパスワード
     */
    protected function _setPassword($password)
    {
        $hash = (new DefaultPasswordHasher)->hash($password);
        Log::write('debug', '_setPassword: ' + $hash);
        return $hash;
    }

    public function toString()
    {
        return sprintf('name[%s] email[%s] password[****] created[%s] modified[%s] errors[%s]',
                $this->name, $this->email, json_encode($this->created), json_encode($this->modified), json_encode($this->errors()));
    }
}
