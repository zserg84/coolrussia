<?php
echo $this->render('/../frontend/performer/list', [
    'dataProvider' => $dataProvider,
    'favorite' => $favorite,
]);