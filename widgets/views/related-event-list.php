<?php
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\helpers\Html;
?>
<?php if ($dataProvider->models): ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Kapcsolódó események</h3>
        </div>
        <div class="box-body">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['tag' => 'div', 'class' => 'list-group list-group-unbordered'],
                'itemOptions' => ['tag' => false],
                'itemView' => function($model) { return '
                    <a href="' . Url::to(['/event/default/view', 'id' => $model->id]) . '" class="list-group-item" style="padding: 10px 5px;">
                        <h4 class="list-group-item-heading">' . $model->title . '</h4>
                        <p class="list-group-item-text">' . count($model->participates) . ' résztvevő, ' . Yii::$app->formatter->asDate($model->start, (date('Y') != date('Y', strtotime($model->start)) ? 'yyyy. ' : '') . 'MMM dd. (EE) HH:mm') . '</p>
                    </a>
                    '; },
                'summary' => '',
            ]) ?>
        </div>
    </div>
<?php endif ?>