<?php
declare(strict_types=1);

use app\models\User;
use app\common\rbac\CollectionRolls;
use yii\db\Migration;

class m210630_174414_create_roles_user_table extends Migration
{
    public const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        try {
            $roleAdmin = $auth->createRole(CollectionRolls::ROLE_ADMIN);
            $auth->add($roleAdmin);
            echo 'Create role ROLE_ADMIN'. PHP_EOL;
            $role = $auth->createRole(CollectionRolls::ROLE_USER);
            $auth->add($role);
            $auth->addChild($roleAdmin, $role);
            echo 'Create role ROLE_USER'. PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function safeDown()
    {
        $this->truncateTable($this->table);
    }
}
