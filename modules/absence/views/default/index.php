<?php
use evolun\event\modules\absence\assets\AbsenceAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

AbsenceAsset::register($this);

$this->registerJsVar('absenceListUrl', Url::to(['absences', 'id' => $event->id]));
$this->registerJsVar('kidListUrl', Url::to(['kids', 'id' => $event->id]));
$this->registerJsVar('deleteAbsenceUrl', Url::to(['delete', 'id' => $event->id]));
$this->registerJsVar('newAbsenceUrl', Url::to(['create', 'id' => $event->id]));
$this->registerCss('
.icons svg {
    height: 1em !important;
    width: 1em !important;
}
    ');
?>

<div id="absences">
    <div class="alert alert-danger alert-dissmissible" v-if="errors.length" v-cloak>
        <h4><i class="icon fa fa-ban"></i> <?= Yii::t('event', 'Error') ?></h4>
        <div v-for="error in errors">{{error}}</div>
    </div>

    <div class="text-muted" v-if="absences && absences.length == 0" v-cloak><p><?= Yii::t('event/absence', 'There are no absences') ?></p></div>

    <table class="table table-hover" v-cloak v-if="absences && absences.length">
        <tr v-for="kid in absences">
            <td>
                <?php if (Yii::$app->user->can('showKids')): ?>
                    <a :href="kid.url" class="text-default">
                <?php endif ?>
                <div class="media">
                    <div class="media-left media-middle">
                        <img :src="kid.image" class="img-circle" width="40">
                    </div>
                    <div class="media-body media-middle">
                        <strong>{{kid.name}} ({{kid.family}})</strong>
                        <div class="text-muted">{{kid.reason}}</div>
                    </div>
                </div>
                <?php if (Yii::$app->user->can('showKids')): ?>
                    </a>
                <?php endif ?>
            </td>
            <?php if (Yii::$app->user->can('manageEvents', ['event' => $event])): ?>
                <td style="vertical-align: middle">
                    <button class="btn btn-danger btn-sm" v-on:click="deleteKid(kid.id)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            <?php endif ?>
        </tr>
    </table>

    <?php if (Yii::$app->user->can('manageEvents', ['event' => $event])): ?>
        <p><strong><?= Yii::t('event/absence', 'Add new absence') ?></strong></p>
        <div>
            <?= Html::beginForm(null, 'post', ['v-on:submit' => 'newAbsence($event)', 'v-on:submit.prevent' => true]) ?>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <vue-single-select
                            name="Absence[kid_id]"
                            v-model="form.kid"
                            :options="kidList"
                            placeholder="<?= Yii::t('event/absence', 'Choose a kid') ?>"
                            :get-option-description="singleSelectLabel"
                            option-key="id"></vue-single-select>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?= Html::activeTextInput($model, 'reason', ['v-model' => 'form.reason', 'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('reason')]) ?>
                </div>
            </div>

            <?= Html::submitButton(Yii::t('event/absence', 'Send'), ['class' => 'btn btn-success']) ?>

            <?= Html::endForm() ?>
        </div>
    <?php endif ?>
</div>

