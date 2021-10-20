<?php
declare(strict_types=1);

namespace app\modules\admin\search;

use app\models\Post;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

class PostSearch extends Post
{
    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'user_id',
                    'message',
                    'moderated',
                    'created'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function search(array $params): array|object
    {
        $query = Post::find();

        $this->load($params);

        if ($this->message !== null && $this->message !== '') {
            $query->andWhere(['like', 'post.message', $this->message]);
        }
        if ( $this->moderated != '') {
            $query->andWhere(['post.moderated' => $this->moderated]);
        }
        if ( $this->id != '') {
            $query->andWhere(['post.id' => $this->id]);
        }
        if ($this->user_id !== null && $this->user_id != '') {
            $query->joinWith(['user']);
            $query->andWhere([ 'like','user.username', $this->user_id]);
        }

        return \Yii::createObject(
            [
                'class' => ActiveDataProvider::class,
                'query' => $query,
                'pagination' => [
                    'params' => $params,
                    'pageSize' => 10,
                ]
            ]
        );
    }

}
