<?php
declare(strict_types=1);

namespace app\models;

use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

class User extends ActiveRecord implements IdentityInterface
{

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    #[ArrayShape(['timestamp' => "array"])]
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['createdAt', 'updatedAt'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt']
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
        return '{{%user}}';
    }

    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    #[ArrayShape([
        'id' => "string",
        'username' => "string",
        'email' => "string",
        'status' => "string",
        'createdAt' => "string",
        'updatedAt' => "string"
    ])]
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'E-mail',
            'status' => 'Статус',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновленно'
        ];
    }

    public static function findIdentity($id): self|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotFoundHttpException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername(string $username): ?self
    {
        return static::findOne([
            'username' => $username,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public static function findByPasswordResetToken(string $token): ?self
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }


    public static function findByVerificationToken(string $token): ?self
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    #[Pure]
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    public static function getListStatus(): array
    {
        return [
            self::STATUS_INACTIVE => 'Не активный',
            self::STATUS_ACTIVE => 'Активный'
        ];
    }

    public function getStatusValue(): string
    {
        $list = self::getListStatus();

        return $list[$this->status];
    }
}
