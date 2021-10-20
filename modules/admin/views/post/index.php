<?php

use app\models\Post;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\grid\GridView;

/**
* @var ActiveQuery $search
* @var ActiveDataProvider $dataProvider
*/

$this->title = 'Наказания';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">
    <div class="row">
         <div class="col-md-2"><?= Html::a('Сбросить фильтр', [''], ['class' => 'btn btn-outline-secondary']); ?></div>
    </div>
    <?= GridView::widget([
        'filterModel' => $search,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
            'id',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return $model->user->username;
                },
            ],
            [
                'attribute' => 'message',
                'format' => 'text'
            ],
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'moderated',
                'value' => function($model) {
                    return $model->getModeratedValue();
                },
                'filter' => Post::getListModerationValue()
            ],
        ]
    ]);
    ?>
</div>
