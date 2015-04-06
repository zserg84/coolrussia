<?php
/**
 * @var modules\event\models\form\EventForm2 $model
 */

use modules\event\Module;
use modules\lang\models\Lang;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;

$curLang = null;
foreach($models as $model) {
    if ($model->hasErrors())
        $curLang = $model->language;
}
if(!$curLang && $languageSelectedList)
    $curLang = $languageSelectedList[0];

if(!$curLang)
    $curLang = Lang::getCurrent();

$languageData = ArrayHelper::map(Lang::getLangArr($languageSelectedList), 'id', 'name');
$languageArr = [];
foreach($languageSelectedList as $ll){
    $languageArr[$ll->id] = $ll->id;
}
$languageStr = implode(",", $languageArr);

$form = ActiveForm::begin([
    'id' => 'event-form',
]); ?>
<?=Html::hiddenInput('languageList', $languageStr, [
    'id' => 'hiddenLanguageList',
])?>
    <div class="language-add">
        <?
        Pjax::begin(['id'=>'pjax-languages-container', 'enablePushState'=>false]);
        echo $this->render('_language_list', [
            'languageSelectedList' => $languageSelectedList,
            'languageData' => $languageData,
            'selectedLanguage' => $curLang->id,
        ]);
        Pjax::end()?>
    </div>
    <div class="form-container">
        <?
        foreach($models as $model):
            echo $this->render('_step2_form', [
                'model' => $model,
                'form' => $form,
                'curLang' => $curLang,
            ]);
        endforeach?>
    </div>

    <div>
        <?= Html::submitButton(Module::t('event', 'FRONTEND_NEXT_SUBMIT'),
            [
                'class' => 'btn btn-primary btn-large'
            ]
        ) ?>
    </div>
<?php ActiveForm::end(); ?>

<?
$this->registerJs(
    '
    removeButtonVisible();

    function activateTab(activeLang){
        var lang;
        $(".language-item").removeClass("active");
        $(".language-item").each(function(){
            if($(this).data("lang")==activeLang)
                $(this).addClass("active");
        });

        $(".form-container .form-lang-tab").each(function(){
            if($(this).data("lang") == activeLang){
                $(this).addClass("active");
            }
            else{
                $(this).removeClass("active");
            }
        });
    }

    function getSelectedLanguage(){
        return $(".selectedLanguage").data("lang");
    }

    function removeButtonVisible(){
        var display = $("#pjax-languages-container").find(".language-item").length > 1 ? "" : "none";
        $(".language-item .remove").css("display", display);
    }

    function buttonAddVisible(visible){
        var container = $("#pjax-languages-container");
        if(visible){
            $(container).find(".buttons .add").addClass("active");
            $(container).find(".buttons .select-language").removeClass("active");
        }
        else{
            $(container).find(".buttons .add").removeClass("active");
            $(container).find(".buttons .select-language").addClass("active");
        }
    }

    $(document).on("click", ".language-item .language-name", function(){
        var langItem = $(this).closest(".language-item");
        var activeLang = $(langItem).data("lang");
        activateTab(activeLang);
    });

    $(document).on("click", ".language-item .remove", function(){
        var langItem = $(this).closest(".language-item");
        var deletedLanguage = $(langItem).data("lang");

        var languageSelectedList = [];
        $("#pjax-languages-container").find(".language-item").each(function(){
            languageSelectedList[$(this).data("lang")] = $(this).data("lang");
        });
        var url = "'.Url::toRoute('lang-deselect').'";
        $.pjax({
            url: url,
            data: {languageSelectedList: languageSelectedList, deletedLanguage: deletedLanguage, selectedLanguage: getSelectedLanguage()},
            container: "#pjax-languages-container",
            push: false,
            replace: false
        });
    });

    $(document).on("click", "#pjax-languages-container .buttons .add", function(){
        buttonAddVisible(false);
    });

    $("#event-form").submit(function(event){
        var langArr = [];
        $("#pjax-languages-container").find(".language-item").each(function(){
            langArr[$(this).data("lang")] = $(this).data("lang");
        });

        $(".form-container .form-lang-tab").each(function(){
            if($.inArray($(this).data("lang"), langArr)==-1){
                $(this).remove();
            }
        });
    });



    $("#pjax-languages-container").on("pjax:end", function() {
        var langArr = [];
        var i = 0;

        var langContainer = this;
        var activeLang;
        var hiddenLanguageList = "";
        $(this).find(".language-item").each(function(){
            if($(this).data("lang") == getSelectedLanguage()){
                $(this).addClass("active");
                activeLang = $(this).data("lang");
            }
            else{
                $(this).removeClass("active");
            }
        });

        activateTab(activeLang);
        removeButtonVisible();
        buttonAddVisible(true);
    }
    );',  View::POS_READY
);

$this->registerCss('
    .form-lang-tab{
        display: none;
    }
    .form-lang-tab.active{
        display: block;
    }
    .language-item.active{
        background: grey;
    }

    #pjax-languages-container .buttons span.select-language, #pjax-languages-container .buttons span.add{
        display: none;
    }
    #pjax-languages-container .buttons span.active{
        display: block;
    }
');
?>