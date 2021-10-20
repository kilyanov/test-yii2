<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use app\models\Post;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Редактирование поста №';
$this->params['breadcrumbs'][] = [
        'label' => 'Посты',
    'url' => ['/admin/post']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'message')->textarea() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'moderated')->dropdownList(Post::getListModerationValue(),['prompt' => '']) ?>
        </div>
    </div>

        <div class="form-group">
            <div class="col-lg-12">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
