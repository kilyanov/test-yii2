<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m211020_115451_create_post_table extends Migration
{
    public const TABLE_NAME = 'post';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $user = '{{%user}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('Пользователь'),
            'message' => $this->text()->notNull()->comment('Содержание'),
            'moderated' => $this->boolean()->notNull()->defaultValue(false)->comment('Модерация'),
            'created' => $this->dateTime()->notNull()->comment('Дата создания')
        ]);
        $this->createIndex(
            'idx-user_id-' . self::TABLE_NAME,
            $this->table,
            'user_id'
        );
        $this->addForeignKey(
            'fk-user_id-' . self::TABLE_NAME,
            $this->table,
            'user_id',
            $this->user,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user_id-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
