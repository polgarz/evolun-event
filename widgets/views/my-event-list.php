<?php
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\helpers\Html;
use evolun\event\assets\EventAsset;

$bundle = EventAsset::register($this);

Yii::$app->getModule($eventModuleId);
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('event/widget', 'Your events') ?></h3>
    </div>
    <div class="box-body">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['tag' => 'div', 'class' => 'list-group list-group-unbordered'],
            'itemOptions' => ['tag' => false],
            'emptyText' => Yii::t('event/widget', 'You don\'t have any event yet'),
            'itemView' => function ($model) use (&$bundle) {
                $layout = '
                    <a href="{url}" class="list-group-item">
                        <div class="media">
                            <div class="media-left media-middle">
                                <div class="img-circle text-center" title="{category}" style="background-color: {color}; width: 40px; height: 40px; justify-content: center; align-items: center; display: flex;">
                                    {icon}
                                </div>
                            </div>
                            <div class="media-body">
                                <strong>{title}</strong>
                                <div class="text-muted">
                                    {participates} &bullet; <span title="{start}">{relative}</span> &bullet; {place}
                                </div>
                            </div>
                        </div>
                    </a>
                ';

                return strtr($layout, [
                    '{title}' => $model->title,
                    '{place}' => $model->place,
                    '{start}' => Yii::$app->formatter->asDate($model->start, (date('Y') != date('Y', strtotime($model->start)) ? 'yyyy. ' : '') . 'MMM dd. (EE) HH:mm'),
                    '{relative}' => Yii::$app->formatter->asRelativeTime($model->start),
                    '{url}' => Url::to(['/event/default/view', 'id' => $model->id]),
                    '{color}' => $model->categoryDetails['color'] ?? '#ccc',
                    '{category}' => $model->categoryDetails['title'] ?? null,
                    '{icon}' => isset($model->categoryDetails['icon']) ? Html::img($bundle->baseUrl . '/svg/categories/' . $model->categoryDetails['icon'], ['width' => '35']) : null,
                    '{participates}' => Yii::t('event/widget', '{participates} going', ['participates' => count($model->participates)]),
                ]);
            },
            'summary' => '',
        ]) ?>
    </div>
    <div class="box-footer">
        <a href="<?= Url::to(['/event/default/index']) ?>"><?= Yii::t('event/widget', 'More events') ?></a>
    </div>
</div>