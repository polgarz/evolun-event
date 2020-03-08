<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model evolun\user\models\User */

$this->title = Yii::t('event', 'New event');
$this->params['pageHeader'] = ['title' => $this->title];
$this->params['breadcrumbs'][] = ['label' => Yii::t('event', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'userList' => $userList,
]);

