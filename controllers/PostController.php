<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostForm;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class PostController extends Controller
{

    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): string
    {
        $posts = Post::find()->isModerated()->orderBy(['created' => SORT_DESC]);
        $dataProvider = Yii::createObject(
            [
                'class' => ActiveDataProvider::class,
                'query' => $posts,
                'pagination' => [
                    'params' => Yii::$app->request->getQueryParams(),
                    'pageSize' => 10,
                ]
            ]
        );

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate(): Response|string
    {
        $model = new PostForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = new Post(ArrayHelper::merge(['user_id' => Yii::$app->user->getId()],$model->getAttributes()));
            if($post->save()) {
                Yii::$app->session->setFlash('success', 'Пост успешно созда, после модерации он появиться в списке постов.');
            }
            else{
                Yii::$app->session->setFlash('error', 'Ошибка создания поста.');
            }

            return $this->redirect(['/post']);
        }
        return $this->render('create', ['model' => $model]);
    }

}
