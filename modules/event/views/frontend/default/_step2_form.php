<?
$activeClass = ($curLang->id == $model->language->id) ? 'active' : '';
?>
<div class="form-lang-tab <?=$activeClass?>" data-lang='<?=$model->language->id?>'>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title')->textInput(
                [
                    'id' => 'eventform2-title-lang-'.$model->language->id,
                    'name'=>'Lang['.$model->language->id.'][EventForm2][title]'
                ]
            )?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'video')->textInput(
                [
                    'name'=>'Lang['.$model->language->id.'][EventForm2][video]'
                ]
            )?>
        </div>
    </div>

    <div>
        <?foreach($model->questions as $k=>$questionModel):?>
            <?
            $question = $questionModel->question;
            echo $form->field($model, $question)->textarea(
                [
                    'name'=>'Lang['.$model->language->id.'][EventForm2]['.$question.']'
                ]
            );
            ?>
        <?endforeach;?>
    </div>
</div>