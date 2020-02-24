<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;
use yii\web\View;
use evolun\event\assets\EventAsset;

/* @var $this yii\web\View */
/* @var $model evolun\user\models\Event */

$this->title = $model->title . ' (' . Yii::$app->formatter->asDate($model->start, 'medium') . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('event', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHeader'] = ['title' => '&nbsp;'];

$bundle = EventAsset::register($this);

$this->registerJsVar('setAttendOptionsUrl', Url::to(['set-attend-options', 'id' => $model->id]));
?>
<div class="row">
    <div class="col-lg-3 col-md-4">
         <!-- Profile Image -->
        <div class="box box-default">
            <div class="box-body box-profile">
                <div class="img-circle profile-user-img img-responsive text-center" title="{category}" style="background-color: <?= $model->categoryDetails['color'] ?>; width: 100px; height: 100px; justify-content: center; align-items: center; display: flex;">
                    <?= ($model->categoryDetails['icon'] ? Html::img($bundle->baseUrl . '/svg/categories/' . $model->categoryDetails['icon'], ['width' => '80']) : '') ?>
                </div>
                <h3 class="profile-username text-center"><?= $model->title ?></h3>

                <p class="text-muted text-center">
                    <?= $model->dateSummary ?>
                </p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b><?= $model->getAttributeLabel('place') ?></b> <a target="_blank" href="mailto:<?= $model->place ?>" class="pull-right"><?= StringHelper::truncate($model->place, 25) ?></a>
                    </li>
                    <li class="list-group-item">
                        <b><?= $model->getAttributeLabel('category') ?></b> <div class="pull-right"><?= $model->categoryDetails['title'] ?></div>
                    </li>
                    <!--
                    <li class="list-group-item">
                        <b><?= $model->getAttributeLabel('created_at') ?></b> <div class="pull-right"><?= Yii::$app->formatter->asDate($model->created_at) ?></div>
                    </li>
                    <?php if ($model->createdBy): ?>
                        <li class="list-group-item">
                            <b><?= $model->getAttributeLabel('created_by') ?></b> <div class="pull-right"><?= (Yii::$app->user->can('showUsers') ? Html::a($model->createdBy->name, ['/users/default/view', 'id' => $model->created_by]) : $model->createdBy->name) ?></div>
                        </li>
                    <?php endif ?>
                    -->
                    <li class="list-group-item">
                        <b><?= $model->getAttributeLabel('organizer_user_id') ?></b>
                        <div class="pull-right">
                            <?php if ($model->organizer): ?>
                                <?= (Yii::$app->user->can('showUsers') ? Html::a($model->organizer->name, ['/users/default/view', 'id' => $model->created_by]) : $model->organizer->name) ?>
                                <?php if (Yii::$app->user->can('setOrganizer')): ?>
                                    <?php if ($model->organizer_user_id == Yii::$app->user->id): ?>
                                        (<?= Html::a(Yii::t('event', 'cancel'), ['set-organizer', 'id' => $model->id, 'cancel' => 1], ['data-method' => 'post']) ?>)
                                    <?php endif ?>
                                <?php endif ?>
                            <?php else: ?>
                                <?= Yii::t('event', 'No one yet') ?>
                                <?php if (Yii::$app->user->can('setOrganizer')): ?>
                                    (<?= Html::a(Yii::t('event', 'apply'), ['set-organizer', 'id' => $model->id], ['data-method' => 'post']) ?>)
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </li>
                </ul>

                <?php if (Yii::$app->user->can('manageEvents')): ?>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><?= Html::a('<i class="fa fa-pencil"></i> ' . Yii::t('event', 'Update'), ['/event/default/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-block']) ?></p>
                        </div>
                        <div class="col-xs-6">
                            <p><?= Html::a('<i class="fa fa-trash"></i> ' . Yii::t('event', 'Delete'), ['/event/default/delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-block', 'data-confirm' => Yii::t('event', 'Are you sure? Every data belongs this event will be deleted!')]) ?></p>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (($model->end && $model->end > date('Y-m-d H:i:s'))
                    || (!$model->end && $model->start > date('Y-m-d H:i:s'))): ?>
                    <?php if (!$attendance): ?>
                        <p><?= Html::a('<i class="fa fa-calendar-check-o"></i> ' . Yii::t('event', 'I will be there'), ['attend', 'id' => $model->id], ['class' => 'btn btn-success btn-block', 'data-method' => 'post']) ?></p>
                    <?php else: ?>
                        <p><?= Html::a('<i class="fa fa-calendar-minus-o"></i> ' . Yii::t('event', 'Cancel'), ['cancel-attend', 'id' => $model->id], ['class' => 'btn btn-warning btn-block', 'data-method' => 'post']) ?></p>

                        <p>
                            <div class="row">
                                <?php if (isset($model->categoryDetails['roles'])): ?>
                                    <div class="col-xs-6">
                                        <div class="btn-group btn-block">
                                            <button type="button" class="btn btn-block btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span class="ellipsis" id="role_title"><?= $model->categoryDetails['roles'][$attendance->role]['name'] ?? $attendance->role ?></span> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu btn-block">
                                                <?php foreach($model->categoryDetails['roles'] as $id => $role): ?>
                                                    <?php /* ha betelt, nem is mutatjuk */ ?>
                                                    <?php if (count($model->participatesByRole[$id] ?? []) >= $role['limit'] && $role['limit'] > 0) continue ?>
                                                    <li>
                                                        <?= Html::a($role['name'], 'javascript:;', ['onclick' => 'setParticipateRole("' . $id . '")']) ?>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if (count($model->days) > 1): ?>
                                    <div class="col-xs-6">
                                        <div class="btn-group btn-block">
                                            <?= Html::dropdownList(
                                                'participate_days',
                                                ArrayHelper::getColumn($attendance->days, 'date'),
                                                ArrayHelper::map(
                                                    $model->days,
                                                    function($d) { return $d->format('Y-m-d'); },
                                                    function($d) { return Yii::$app->formatter->asDate($d, 'MMMM dd. (EE)'); }
                                                ),
                                                ['multiple' => true, 'id' => 'participate_days', 'data-n-selected-text' => Yii::t('event', 'days'), 'data-all-selected-text' => Yii::t('event', 'Whole event')]
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </p>
                    <?php endif ?>
                <?php endif ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <?php if (!empty(Yii::$app->controller->module->widgets)): ?>
            <?php foreach(Yii::$app->controller->module->widgets as $widget): ?>
                <?= $widget::widget(['event' => $model]) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <!-- /.col -->

    <!-- right col -->
    <div class="col-lg-9 col-md-8">
        <?php if ($modules): ?>
            <?php foreach($modules as $id => $module): ?>
                <?php $items[] = ['label' => $module['title'], 'content' => $module['content']] ?>
            <?php endforeach ?>

            <div class="nav-tabs-custom">
                <?= Tabs::widget([
                    'items' => $items,
                ]) ?>
            </div>
        <?php endif ?>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
