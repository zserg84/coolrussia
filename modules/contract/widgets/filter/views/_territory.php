<?
use common\models\Country;
use common\models\State;
use common\models\City;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use modules\users\Module as UserModule;

$countryId = Yii::$app->getRequest()->post('filter_territory_country');
$stateId = Yii::$app->getRequest()->post('filter_territory_state');
$cityId = Yii::$app->getRequest()->post('filter_territory_city');

$countries = Country::find()->all();
$states = State::find()->where(['country_id'=>$countryId])->all();
$cities = City::find();
if($countryId)
    $cities->andWhere(['country_id'=>$countryId]);
if($stateId)
    $cities->andWhere(['state_id'=>$stateId]);
$cities = $cities->all();
?>
<div class="territorial-filtr">
    <?=Html::dropDownList('filter_territory_country', $countryId, ArrayHelper::map($countries, 'id', 'name'), [
        'id'=>'filter-territory-country-id',
        'prompt' => UserModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_COUNTRY'),
    ])?>

    <?= DepDrop::widget([
        'name' => 'filter_territory_state',
        'data'=> ArrayHelper::map($states, 'id', 'name'),
        'value' => $stateId,
        'options'=>[
            'id'=>'filter-territory-state-id',
            'prompt' => UserModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_STATE'),
        ],
        'pluginOptions'=>[
            'depends'=>['filter-territory-country-id'],
            'url'=>'/users/guest/get-states/',
            'placeholder' => UserModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_STATE'),
        ],
        'pluginEvents' => [
            "depdrop.afterChange"=>"function(event, id, value) {
                var oDropdown = $('#filter-territory-state-id').msDropdown().data('dd');
                oDropdown.destroy();
                oDropdown = $('#filter-territory-state-id').msDropdown().data('dd');
                oDropdown.refresh();
            }",
        ],
    ])?>

    <?= DepDrop::widget([
        'name' => 'filter_territory_city',
        'data'=> ArrayHelper::map($cities, 'id', 'name'),
        'value' => $cityId,
        'options'=>[
            'id'=>'filter-territory-city-id',
            'prompt' => UserModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_CITY'),
        ],
        'pluginOptions'=>[
            'depends'=>['filter-territory-country-id', 'filter-territory-state-id'],
            'url'=>'/users/guest/get-cities/',
            'placeholder' => UserModule::t('ALL_INTERFACES', 'TERRITORIAL_FILTER_CITY'),
        ],
        'pluginEvents' => [
            "depdrop.afterChange"=>"function(event, id, value) {
                var oDropdown = $('#filter-territory-city-id').msDropdown().data('dd');
                oDropdown.destroy();
                oDropdown = $('#filter-territory-city-id').msDropdown().data('dd');
                oDropdown.refresh();
            }",
        ],
    ])?>
</div>
<?
$this->registerJs('
    $(document).on("click", ".resetLink", function(ev){
        var form = $(this).closest("form");
        form.find("select").each(function(){
            $(this).prop("selectedIndex", 0);
            oDropdown = $(this).msDropdown().data("dd");
            oDropdown.refresh();
        });
    });
', \yii\web\View::POS_END);
?>