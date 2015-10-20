<?
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\components\Modal;
use modules\themes\Module as ThemeModule;
use modules\contract\widgets\settings\SettingsWidget;

?>
<div class="col-sm-5 ava">
    <img src="<?=$user->getLogo()?>" alt="" widht="75" height="75">
</div>
<div class="col-sm-7 desc">
    <p><?=$user->login?></p>
    <p><a href="javascript:void(0)" class="edit_profile open-modal"><?=ThemeModule::t('ALL_INTERFACES', 'USER_INFO_EDIT')?></a></p>
    <p><a href="#" class="edit_settings"><?=ThemeModule::t('ALL_INTERFACES', 'USER_INFO_SETTINGS')?></a></p>
    <p><?=Html::a(ThemeModule::t('ALL_INTERFACES', 'USER_INFO_EXIT'), '/logout/', ['class' => 'exit'])?></p>
</div>

<?
Modal::begin([
    'id' => 'user-profile-modal',
    'header' => '<p class="title">'.ThemeModule::t('ALL_INTERFACES', 'USER_INFO_PROFILE').' "'.$user->name.'"</p>',
    'clientOptions' => false,
    'options' => [],
]);
Pjax::begin(['id' => 'pjax-user-profile-container', 'enablePushState' => false]);
Pjax::end();
Modal::end();

$this->registerJs('
    $(document).on("click", ".edit_profile", function(){
        var url = "'.Url::toRoute(['/users/user/update/']).'";
        $.pjax({url: url, container: "#pjax-user-profile-container", push: false, replace: false});
    });

    $(document).on("click", ".edit_settings", function(){
        $("#settings-popup").modal();
    });

    $("#pjax-user-profile-container").on("pjax:end", function(event) {
        initPopup();
        $("#user-profile-modal").modal();
    })
');

echo SettingsWidget::widget();

/*не удалять*/
\dosamigos\fileinput\BootstrapFileInput::widget([
    'name' => 'hiddenFileInput'
]);