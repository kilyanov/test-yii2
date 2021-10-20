<?php
declare(strict_types=1);

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\base\Model;

class PostForm extends Model
{
    public const SCENARIO_ADMIN = 'admin';

    public string $message = '';
    public int $moderated = Post::NOT_MODERATED;

    #[ArrayShape(['message' => "string", 'moderated' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'message' => 'Пост',
            'moderated' => 'Модерация',
        ];
    }

    public function rules(): array
    {
        return [
            [['message'], 'required'],
            ['moderated', 'required', 'on' => self::SCENARIO_ADMIN],
            ['message', 'string', 'min' => 10],
        ];
    }
}
