<?php
declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\common\rbac\CollectionRolls;
use app\models\Post;
use app\models\PostForm;
use app\modules\admin\search\PostSearch;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PostController extends Controller
{

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [CollectionRolls::ROLE_ADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $search = new PostSearch();
        $dataProvider = $search->search(\Yii::$app->request->getQueryParams());

        return $this->render(
            'index',
            [
                'search' => $search,
                'dataProvider' => $dataProvider
            ]
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = new PostForm(['scenario' => PostForm::SCENARIO_ADMIN]);
        $post = $this->findModel($id);
        $model->setAttributes($post->getAttributes(['message', 'moderated']));
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $post->setAttributes($model->getAttributes(['message', 'moderated']));
            if($post->save()) {
                \Yii::$app->session->setFlash('success', 'Данные успешно изменёны.');
            }
            else{
                \Yii::$app->session->setFlash('error', 'Ошибка сохранения данных.');
            }

            return $this->redirect(['/admin/post']);
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): ?Post
    {
        $model = Post::findOne(['id' => $id]);
        if($model === null) {
            throw new NotFoundHttpException("Пост не найден.");
        }

        return $model;
    }

}
