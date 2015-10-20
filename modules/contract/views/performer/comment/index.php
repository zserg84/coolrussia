<?php
echo $this->render('/../frontend/comment/index', [
    'comments' => $comments,
    'contract' => $contract,
]);