<h1><?= $comment->event->title . ' (' . $comment->event->dateSummary . ')' ?></h1>
<p>
    <strong><?= $comment->user->name ?></strong> megjegyzése: <?= $comment->comment ?>
</p>