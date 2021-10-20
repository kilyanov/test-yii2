<?php
declare(strict_types=1);

namespace app\common\rbac;

use JetBrains\PhpStorm\ArrayShape;

class CollectionRolls
{

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    #[ArrayShape([self::ROLE_ADMIN => "string", self::ROLE_USER => "string"])]
    public static function getListRole(): array
    {
        return [
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_USER => 'Пользователь',
        ];
    }

}
