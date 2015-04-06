<?php
use kartik\select2\Select2;
use yii\helpers\Url;

foreach($languageSelectedList as $language):
    $activeClass = $language->id == $selectedLanguage ? "active" : "";
    ?>
    <span class="language-item <?=$activeClass?>" data-lang="<?=$language->id?>">
        <input type="hidden" name="hiddenLanguage[]" value="<?=$language->id?>">
        <span class="language-name"><?=$language->name;?></span>
        <span class="remove">X</span>
    </span>
<?endforeach;

echo '<span class="selectedLanguage" data-lang="'.$selectedLanguage.'"></span>';

echo '<span class="buttons">';
    echo '<span class="add active">+</span>';
    echo '<span class="select-language">';
        echo Select2::widget([
            'name' => 'lang_select',
            'data' => $languageData,
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'options' => [
                'id'=>'langId',
                'placeholder'=>'Введите название языка',
                'style' => 'width:200px',
                'onchange' => '
                    var languageSelectedList = [];
                    var i = 0;
                    $(".language-item .language-name").each(function(){
                        languageSelectedList[i] = $(this).html();
                        i++;
                    });

                    var selectedLanguage = $(this).val();
                    var url = "'.Url::toRoute('lang-select').'";
                    $.pjax({
                        url: url,
                        data: {languageSelectedList: languageSelectedList, selectedLanguage: selectedLanguage},
                        container: "#pjax-languages-container",
                        push: false,
                        replace: false
                    });
                ',
            ],
        ]);
    echo '</span>';
echo '</span>';