<?php
use evolun\event\modules\comments\assets\CommentsAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

$this->registerJsVar('commentsListUrl', Url::to(['comments', 'id' => $model->event_id]));
$this->registerJsVar('commentDeleteUrl', Url::to(['delete', 'id' => $model->event_id]));
$this->registerJsVar('newCommentUrl', Url::to(['new-comment', 'id' => $model->event_id]));

CommentsAsset::register($this);
?>

<div id="comments">
    <div class="post" v-for="comment in comments" v-cloak v-if="comments && comments.length > 0">
        <div class="user-block">
            <img class="img-circle img-bordered-sm" :src="comment.user.image" alt="<?= Yii::t('event/comments', 'Profile image') ?>" v-if="comment.user">
            <img src="https://via.placeholder.com/100x100?text=%3F" class="img-circle img-bordered-sm" alt="<?= Yii::t('event/comments', 'Profile image') ?>" v-else>
            <span class="username">
                <?php if (Yii::$app->user->can('showUsers')): ?>
                    <a :href="comment.user.url">{{comment.user.name}}</a>
                <?php else: ?>
                    {{comment.user.name}}
                <?php endif ?>
                <a href="#" v-on:click="deleteComment(comment.id)" v-if="comment.user_id == comment.current_user_id" class="pull-right btn-box-tool">
                    <i class="fa fa-trash"></i>
                </a>
            </span>
            <span class="description">{{comment.date}}</span>
        </div>

        <p>{{comment.comment}}</p>
    </div>

    <div class="alert alert-danger alert-dissmissible" v-if="errors.length" v-cloak>
        <h4><i class="icon fa fa-ban"></i> <?= Yii::t('event', 'Error') ?></h4>
        <div v-for="error in errors">{{error}}</div>
    </div>

    <div class="text-muted" v-if="comments && comments.length == 0" v-cloak><p><?= Yii::t('event/comments', 'There are no comments') ?></p></div>

    <?php if (Yii::$app->user->can('showEvents')): ?>
        <p><strong><?= Yii::t('event/comments', 'New comment') ?></strong></p>
        <!-- uj dokumentum -->
        <div>
            <?= Html::beginForm(null, 'post', ['v-on:submit' => 'newComment($event)', 'v-on:submit.prevent' => true]) ?>

            <div class="form-group">
                <?= Html::activeTextArea($model, 'comment', ['v-model' => 'form.comment', 'class' => 'form-control', 'rows' => 3, 'placeholder' => $model->getAttributeLabel('comment')]) ?>
                <div class="help-block"></div>
            </div>

            <?= Html::submitButton(Yii::t('event/comments', 'Send'), ['class' => 'btn btn-success']) ?>

            <?= Html::endForm() ?>
        </div>
    <?php endif ?>
</div>