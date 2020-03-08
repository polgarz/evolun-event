<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use evolun\event\assets\EventAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('event', 'Events');
$this->params['pageHeader'] = ['title' => $this->title];
$this->params['breadcrumbs'][] = $this->title;

$bundle = EventAsset::register($this);
?>

<div class="box box-default">

    <?php if (Yii::$app->user->can('manageEvents')): ?>
        <div class="box-header">
            <div class="box-tools pull-left">
                <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('event', 'New event'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php endif ?>

    <div class="box-body table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showHeader' => false,
            'tableOptions' => ['class' => 'table table-hover'],
            'rowOptions' => function ($model, $key, $index, $grid) use (&$closestEvent) {
                $options = [];

                if ($closestEvent && $model->id === $closestEvent->id) {
                    $options += ['style' => 'border-bottom: 3px dotted #bbb;'];
                }

                if (in_array(Yii::$app->user->id, ArrayHelper::getColumn($model->participates, 'user_id'))) {
                    $options += ['class' => 'bg-warning'];
                }

                return $options;
            },
            'layout' => '{items}{summary}{pager}',
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) use (&$bundle) {

                        $layout = '
                            <a href="{url}" class="text-default">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <div class="img-circle text-center" title="{category}" style="background-color: {color}; width: 40px; height: 40px; justify-content: center; align-items: center; display: flex;">
                                            {icon}
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <strong>{title}</strong>
                                        <div class="text-muted">
                                            {participates} &bullet; {start} &bullet; {place}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        ';

                        return strtr($layout, [
                            '{title}' => $model->title,
                            '{place}' => $model->place,
                            '{start}' => Yii::$app->formatter->asDateTime($model->start, (date('Y') != date('Y', strtotime($model->start)) ? 'yyyy. ' : '') . 'MMM dd. (EE) HH:mm'),
                            '{url}' => Url::to(['view', 'id' => $model->id]),
                            '{color}' => $model->categoryDetails['color'] ?? '#ccc',
                            '{category}' => $model->categoryDetails['title'] ?? null,
                            '{icon}' => isset($model->categoryDetails['icon']) ? Html::img($bundle->baseUrl . '/svg/categories/' . $model->categoryDetails['icon'], ['width' => '35']) : null,
                            '{participates}' => Yii::t('event', '{participates} going', ['participates' => count($model->participates)]),
                        ]);
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'width: 65px; vertical-align: middle; text-align: right'],
                    'template' => '{gcalendar}',
                    'buttons' => [
                        'gcalendar' => function ($url, $model) {
                            $url = strtr('https://www.google.com/calendar/render?action=TEMPLATE&text={title}&dates={start}/{end}&details={description}&location={place}&ctz=UTC', [
                                '{title}' => urlencode($model->title),
                                '{place}' => urlencode($model->place),
                                '{description}' => urlencode($model->description),
                                '{start}' => date('Ymd\\THi00', strtotime($model->start)),
                                '{end}' => $model->end ? date('Ymd\\THi00', strtotime($model->end)) : null,
                            ]);
                            return Html::a('<i class="fa fa-calendar-plus-o"></i><span class="hidden-xs hidden-sm hidden-md"> ' . Yii::t('event', 'Add to calendar') . '</span>', $url, ['class' => 'btn btn-default btn-sm', 'target' => '_blank']);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
