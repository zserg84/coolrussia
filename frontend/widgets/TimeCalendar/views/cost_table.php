<?php
if($values){
    foreach($values as $value){
        echo $this->render('cost_item', [
            'value' => $value,
        ]);
    }
}
else
    echo $this->render('cost_item',[
        'value' => false,
    ]);