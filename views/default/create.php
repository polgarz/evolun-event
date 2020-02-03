<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model evolun\user\models\User */

$this->title = 'Új esemény';
$this->params['pageHeader'] = ['title' => $this->title];
$this->params['breadcrumbs'][] = ['label' => 'Események', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'userList' => $userList,
]) ?>

