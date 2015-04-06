<div class="language-add-widget">
    <span class="language-item">
        <span class="active"><?=$curLanguage->name;?></span>
        <span class="remove">X</span>
    </span>
    <span class="add">+</span>
</div>
<?
$current = \modules\lang\models\Lang::getCurrent();
$langs = \yii\helpers\ArrayHelper::map(\modules\lang\models\Lang::getLangArr([$current]), 'id', 'name');
echo \kartik\select2\Select2::widget([
    'name' => 'sad',
    'data' => $langs,
    'pluginOptions' => [
        'allowClear' => true,
    ],
    'options' => [
        'id'=>'roleInCompany',
        'placeholder'=>'Введите название языка',
    ],
]);

echo $this->render('view');
?>