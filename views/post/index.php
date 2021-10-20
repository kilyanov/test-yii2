<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \app\models\Post
 **/

use yii\bootstrap4\LinkPager;

$this->title = 'Список постов';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">
    <div class="row">
        <div class="col-md-4">
            <?= \yii\bootstrap4\Html::a('Создать пост', ['/post/create'], ['class' => 'btn btn-outline-secondary']); ?>
        </div>
    </div>
    <?php if(count($dataProvider->getModels()) > 0): ?>
    <?php foreach ($dataProvider->getModels() as $model): ?>
    <div class="row">
        <div class="col-md-12">
            <?= $model->message; ?>
            <br/>
            <?= date('d.m.Y H:i:s', strtotime($model->created));?>
            <hr/>
        </div>
    </div>

    <?php endforeach; ?>
    <div class="row">
        <div class="col-md-12">
    <?php
    echo LinkPager::widget([
        'pagination' => $dataProvider->getPagination(),
    ]);

    ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if(count($dataProvider->getModels()) === 0): ?>
        <h3>Постов пока нет.</h3>
    <?php endif; ?>

</div>
