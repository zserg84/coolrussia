<?foreach($comments as $comment):
    $contract = $comment->commentContract;
    if(! ($user = $contract->customer)){
        $company = $contract->performer;
        if(!$company)
            continue;
        $user = $company->user;
        if(!$user)
            continue;
    }
    ?>
    <div class="row response-comment">
        <div class="col-sm-12">
            <p class="gray"><?=Yii::$app->getFormatter()->asDate($comment->created_at)?></p>
            <p><b><?=$user->name?></b></p>
            <p><?=$comment->text?></p>
        </div>
    </div>
<?endforeach?>