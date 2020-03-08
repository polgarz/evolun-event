<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use evolun\event\assets\EventAsset;

$bundle = EventAsset::register($this);
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title"><?= Yii::t('event', 'Basic info') ?></h3>
        </div>

        <div class="box-body">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'category')->dropdownList(array_map(function ($item) {
                return $item['title'];
            }, Yii::$app->controller->module->categories)) ?>

            <?= $form->field($model, 'organizer_user_id')->widget(Select2::classname(), [
                'data' => $userList,
                'theme' => 'default',
                'options' => ['prompt' => Yii::t('event', 'Choose later')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>

            <?= $form->field($model, 'start')->widget(DateTimePicker::classname(), [
                'pluginOptions' => [
                    'autoclose'      => true,
                    'format'         => 'yyyy-mm-dd hh:ii',
                    'todayHighlight' => true
                ]
            ]) ?>

            <?= $form->field($model, 'end')->widget(DateTimePicker::classname(), [
                'pluginOptions' => [
                    'autoclose'      => true,
                    'format'         => 'yyyy-mm-dd hh:ii',
                    'todayHighlight' => true
                ]
            ]) ?>

            <?= $form->field($model, 'place')->textInput() ?>

            <?= $form->field($model, 'description')->textArea(['rows' => 6, 'id' => 'summernote']) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('event', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

