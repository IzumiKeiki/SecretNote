<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher; 
use Cake\ORM\Entity;

/**
 * SecretNote Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $note
 * @property \Cake\I18n\DateTime $create_time
 * @property \Cake\I18n\DateTime $update_time
 * @property int $update_token
 */
class SecretNote extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'username' => true,
        'password' => true,
        'note' => true,
        'create_time' => true,
        'update_time' => true,
        'update_token' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }
}
