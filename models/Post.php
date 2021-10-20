<?php
declare(strict_types=1);

namespace app\models;

use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property string $message Содержание
 * @property int $moderated Модерация
 * @property string $created Дата создания
 *
 * @property User $user
 */
class Post extends ActiveRecord
{

    public const IS_MODERATED = 1;
    public const NOT_MODERATED = 0;

    #[ArrayShape([self::NOT_MODERATED => "string", self::IS_MODERATED => "string"])]
    public static function getListModerationValue(): array
    {
        return [
            self::NOT_MODERATED => 'Нет',
            self::IS_MODERATED => 'Да'
        ];
    }

    #[Pure]
    public function getModeratedValue(): string
    {
        $list = self::getListModerationValue();

        return $list[$this->moderated];
    }

    #[ArrayShape(['timestamp' => "array"])]
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created',],
                ],
                'value' => function () {
                    $carbon = new Carbon();
                    $carbon->format('Y-m-d H:i:s');
                    return $carbon->toDateTimeString();
                }
            ],
        ];
    }

    public static function tableName(): string
    {
        return '{{%post}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'message'], 'required'],
            [['user_id', 'moderated'], 'integer'],
            ['moderated', 'default', 'value' => self::IS_MODERATED],
            [['message'], 'string'],
            [['created'], 'safe'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    #[ArrayShape([
        'id' => "string",
        'user_id' => "string",
        'message' => "string",
        'moderated' => "string",
        'created' => "string"
    ])]
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'message' => 'Содержание',
            'moderated' => 'Модерация',
            'created' => 'Дата создания',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function find(): ActiveQuery
    {
        return new PostQuery(get_called_class());
    }

}
