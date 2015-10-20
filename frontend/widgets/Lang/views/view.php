<?
Use modules\themes\Module as ThemeModule;
?>
<div class="lang col-sm-2" >
    <p><?=ThemeModule::t('Horizontal menu ALL pages', 'SELECT_LANGUAGE_ALL_PAGES')?>:</p>
    <select id="tender-lang">
        <?php foreach ($langs as $lang):
            $selected = $lang->id == $curLang->id ? 'selected' : '';
            ?>
            <option <?=$selected?> value="<?=$lang->url ?>">
                <?=$lang->name ?>
            </option>
        <?php endforeach;?>
    </select>
</div>

<?
$url = Yii::$app->getRequest()->getLangUrl();
$this->registerJS('
    $(document).on("change", "#tender-lang", function(){
        var lang = $(this).val();
        var url = "'.$url.'";
        if(url.indexOf("?") > 0)
            url += "&";
        else url += "?";
        url += "lang=" + lang;
        window.location.href = url;
    });
', \yii\web\View::POS_READY);
?>