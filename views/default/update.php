<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model evolun\user\models\User */

$this->title = Yii::t('event', 'Update event: {title}', ['title' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('event', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('event', 'Update');
$this->params['pageHeader'] = ['title' => $this->title];
?>

<?= $this->render('_form', [
    'model' => $model,
    'userList' => $userList,
]) ?>