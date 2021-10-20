<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function isModerated(): ActiveQuery
    {
        return $this->andWhere(['moderated' => Post::IS_MODERATED]);
    }

}
