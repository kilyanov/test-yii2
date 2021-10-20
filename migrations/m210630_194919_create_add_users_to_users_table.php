<?php

use Carbon\Carbon;
use app\models\User;
use app\common\rbac\CollectionRolls;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

/**
 * Handles the creation of table `{{%add_users_to_users}}`.
 */
class m210630_194919_create_add_users_to_users_table extends Migration
{
    public const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    private array $listUser = [
        [
            'username' => 'admin',
            'email' => 'admin@test-yii.loc',
            'role' => CollectionRolls::ROLE_ADMIN,
        ],
        [
            'username' => 'user',
            'email' => 'user@test-yii.loc',
            'role' => CollectionRolls::ROLE_USER,
        ],
    ];

    /**
     * @throws Exception
     */
    public function safeUp()
    {
        $authManager = Yii::$app->getAuthManager();
        foreach ($this->listUser as $item) {
            $roleName = $item['role'];
            unset($item['role']);
            $user = new User($item);
            $user->generateAuthKey();
            $user->setPassword($item['username']);
            if($user->save()) {
                echo 'ADD user ' . $user->username . PHP_EOL;
                $role = $authManager->getRole($roleName);
                $authManager->assign($role, $user->id);
                echo 'SET role ' . $roleName . PHP_EOL;
            }
        }
    }


    public function safeDown()
    {
        $this->truncateTable($this->table);
    }
}
