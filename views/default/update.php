<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model evolun\user\models\User */

$this->title = 'Esemény adatainak módosítása: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Események', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Módosítás';
$this->params['pageHeader'] = ['title' => $this->title];
?>

<?= $this->render('_form', [
    'model' => $model,
    'userList' => $userList,
]) ?>