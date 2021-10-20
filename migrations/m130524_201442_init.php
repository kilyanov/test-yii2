<?php
declare(strict_types=1);

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public const TABLE_NAME = 'user';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function up()
    {
        $tableOptions = null;

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('Логин'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ'),
            'password_hash' => $this->string()->notNull()->comment('Пароль'),
            'password_reset_token' => $this->string()->unique()->defaultValue(null)->comment('Токен для сброса пароля'),
            'email' => $this->string()->notNull()->unique()->comment('Email'),
            'verification_token' => $this->string()->defaultValue(null)->comment('Токен верификации'),

            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
