<?
/**
 * @var yii\web\View $this
 * @var yii\base\Model $model
 * @var array $pageParams
 * @var string $page
 */

echo $this->render('_wizard', ['page'=>$page, 'pageParams'=>$pageParams]);
echo $this->render($page, $pageParams);