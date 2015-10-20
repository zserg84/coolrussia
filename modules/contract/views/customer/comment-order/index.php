<?php
echo $this->render('/../frontend/comment-order/index', [
    'comments' => $comments,
    'order' => $order,
]);