<?php

namespace modules\blog\controllers\frontend;

use modules\blog\models\BlogsLang;
use modules\blog\models\frontend\Blog;
use modules\users\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;

/**
 * Default controller.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (!isset($behaviors['access']['class'])) {
            $behaviors['access']['class'] = AccessControl::className();
        }

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['index', 'view'],
            'roles' => ['viewBlogs']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get']
            ]
        ];

        return $behaviors;
    }

    /**
     * Blog list page.
     */
    function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Blog::find()->published(),
            'pagination' => [
                'pageSize' => $this->module->recordsPerPage
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Blog page.
     *
     * @param integer $id Blog ID
     * @param string $alias Blog alias
     *
     * @return mixed
     *
     * @throws \yii\web\HttpException 404 if blog was not found
     */
    public function actionView($id, $alias)
    {
        if (($model = Blog::findOne(['id' => $id, 'alias' => $alias])) !== null) {
            $blogsLang = BlogsLang::getBlogsLangByBlogAndLang($id)->one();
            $this->counter($model);

            return $this->render('view', [
                'model' => $model,
                'blogsLang' => $blogsLang,
            ]);
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Update blog views counter.
     *
     * @param Blog $model Model
     */
    protected function counter($model)
    {
        $cookieName = 'blogs-views';
        $shouldCount = false;
        $views = Yii::$app->request->cookies->getValue($cookieName);

        if ($views !== null) {
            if (is_array($views)) {
                if (!in_array($model->id, $views)) {
                    $views[] = $model->id;
                    $shouldCount = true;
                }
            } else {
                $views = [$model->id];
                $shouldCount = true;
            }
        } else {
            $views = [$model->id];
            $shouldCount = true;
        }

        if ($shouldCount === true) {
            if ($model->updateViews()) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => $cookieName,
                    'value' => $views,
                    'expire' => time() + 86400 * 365
                ]));
            }
        }
    }
}
