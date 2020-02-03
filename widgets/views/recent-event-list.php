<?php
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\helpers\Html;

use evolun\event\assets\EventAsset;

$bundle = EventAsset::register($this);
?>
<?php if ($dataProvider->models): ?>
    <div class="col-sm-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Közelgő események</h3>
            </div>
            <div class="box-body">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['tag' => 'div', 'class' => 'list-group list-group-unbordered'],
                    'itemOptions' => ['tag' => false],
                    'itemView' => function($model) use (&$bundle) {
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
                                            {participates} résztvevő &bullet; {start}
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
                            '{icon}' => isset($model->categoryDetails['icon']) ? Html::img($bundle->baseUrl . '/dist/svg/categories/' . $model->categoryDetails['icon'], ['width' => '35']) : null,
                            '{participates}' => count($model->participates),
                        ]);
                    },
                    'summary' => '',
                ]) ?>
            </div>
            <div class="box-footer">
                <a href="<?= Url::to(['/event/default/index']) ?>">További események</a>
            </div>
        </div>
    </div>
<?php endif ?>