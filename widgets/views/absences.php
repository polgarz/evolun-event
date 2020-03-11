<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

Yii::$app->getModule($eventModuleId);
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
    </div>
    <div class="box-body">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['tag' => 'div', 'class' => 'list-group list-group-unbordered'],
            'itemOptions' => ['tag' => false],
            'emptyText' => Yii::t('event/widget', 'There are no absences'),
            'itemView' => function ($model) use (&$kids) {
                $kid = $kids[$model['kid_id']];

                $layout = '
                    <a href="{url}" class="list-group-item">
                        <div class="media">
                            <div class="media-left media-middle">
                                <img src="{image}" class="img-circle" width="40" />
                            </div>
                            <div class="media-body">
                                <strong>{name}</strong>
                                <div class="text-muted">
                                    {absences}
                                </div>
                            </div>
                        </div>
                    </a>
                ';

                return strtr($layout, [
                    '{image}' => $kid->getThumbUploadUrl('image', 's'),
                    '{name}' => $kid->name . ' (' . $kid->family . ')',
                    '{absences}' => Yii::t('event/widget', '{absences} absences', ['absences' => $model['absences']]),
                    '{url}' => Url::to(['/kid/default/view', 'id' => $kid->id]),
                ]);
            },
            'summary' => '',
        ]) ?>
    </div>
</div>