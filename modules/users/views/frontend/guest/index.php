<?
use modules\themes\Module as ThemeModule;
?>
<div class="row">
    <div class="main-desc col-sm-10 col-sm-offset-1">
        <p class="title"><?=ThemeModule::t('Homepage', 'TEXT_FOR_THE_HEADING_OF_GREETING')?></p>
        <p><?=ThemeModule::t('Homepage', 'TEXT_OF_GREETING')?></p>
    </div>
</div>
<!-- end desc -->

<!-- role -->
<div class="row">
    <div class="role">
        <div class="col-sm-3 col-sm-offset-3">
            <a href="#customer-reg-popup" class="btn btn-default open-popup" id="customer-reg-button"><?=ThemeModule::t('Homepage', 'BUTTON_FOR_CUSTOMER')?></a>
        </div>
        <div class="col-sm-3">
            <a href="#performer-reg-popup" class="btn btn-default open-popup"><?=ThemeModule::t('Homepage', 'BUTTON_FOR_PERFORMER')?></a>
        </div>
    </div>
</div>
<!-- end role -->
<div id="customer-reg-popup" class="zoom-anim-dialog mfp-hide popup">
    <p class="title"><?=$customerModel->getAttributeLabel('customer_registration')?></p>
    <?=$this->render('_customer_reg_form', [
        'model' => $customerModel,
    ])?>
</div>

<div id="performer-reg-popup" class="zoom-anim-dialog mfp-hide popup">
    <p class="title"><?=$performerModel->getAttributeLabel('performer_registration')?></p>
    <?=$this->render('_performer_reg_form', [
        'model' => $performerModel,
    ])?>
</div>
<?
/*валидация форм*/
/*$js = <<<JS
jQuery('#performer_reg_form, #customer_reg_form').on('beforeSubmit', function(){
    var form = jQuery(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (response, status, xhr) {
            response = xhr.responseJSON;
            response = jQuery.parseJSON(response);
            if(response.output == 'error'){
                $.each(response.error, function (id, msg) {
                    var formgroup = $('#' + id).closest('.form-group');
                    formgroup.addClass("has-error");
                    formgroup.find('.help-block').html(msg);
                });
            }
        }
    });
    return false;
})
JS;
$this->registerJs($js);*/