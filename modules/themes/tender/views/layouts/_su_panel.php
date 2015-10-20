<?
if (YII_DEBUG) {
    $controller = Yii::$app->controller;
    $controllerName = $controller->className();
    $actionName = $controller->action->id;

    ?>
    <div style="position:relative;">
        <div style="position:absolute;top:0;left:0;right:0;z-index:99;">
            <div style="margin:0 auto;width:600px;padding:3px;opacity:0.7;background:#fff;color:#000;">
                <?
                if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
                    ?><input type="hidden" class="__open_ide"
                             value="<?= Yii::getAlias('@root') . '\\' . $controllerName . '.php' ?>" /><?
                }
                ?>
<!--                <input type="text" style="border:none;width:390px;text-align:right;" onclick="this.select()"-->
<!--                       value="--><?//= $controllerName ?><!--"/>-->
<!--                ::-->
<!--                <input type="text" style="border:none;width:190px;" onclick="this.select()"-->
<!--                       value="--><?//= 'action' . ucfirst($actionName) . '()' ?><!--"/>-->
            </div>
        </div>
    </div>
<?
}