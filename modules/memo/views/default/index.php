<?php
use evolun\event\modules\memo\assets\MemoAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

if (Yii::$app->user->can('manageEvents', ['event' => $event])) {
    MemoAsset::register($this);
}

$this->registerJsVar('memoHistoryUrl', Url::to(['history', 'id' => $event->id]));
$this->registerJsVar('memoSaveUrl', Url::to(['save', 'id' => $event->id]));
?>

<?php if (Yii::$app->user->can('manageEvents', ['event' => $event])): ?>
    <div id="memo">
        <div class="alert alert-danger alert-dissmissible" v-if="errors.length" v-cloak>
            <h4><i class="icon fa fa-ban"></i> <?= Yii::t('event', 'Error') ?></h4>
            <div v-for="error in errors">{{error}}</div>
        </div>

        <p>
            <ckeditor :editor="editor" v-model="editorContent" @ready="loadData" @input="autosave" @focus="enableAutosave" @blur="disableAutosave" :config="editorConfig"></ckeditor>
        </p>

        <p>
            <a href="#" v-on:click="save" class="btn btn-success" v-cloak><?= Yii::t('event', 'Save') ?></a>
            <a href="#" onclick="return false;" v-if="history && history.length > 0" class="btn btn-default" data-toggle="collapse" data-target="#history" v-cloak><?= Yii::t('event/memo', 'History') ?> <i class="fa fa-angle-down"></i></a>
        </p>

        <div v-if="history && history.length > 0" id="history" class="collapse" v-cloak>
            <div class="list-group">
                <div class="list-group-item" v-for="memo in history">
                    <a href="#" v-on:click="restore(memo.content)">{{memo.created_by}} ({{memo.created_at}})</a>
                </div>
            </div>
        </div>
    </div>
<?php elseif (!empty($memo->content)): ?>
    <p>
        <?= $memo->content ?>
    </p>
<?php else: ?>
    <div class="text-muted"><p><?= Yii::t('event/memo', 'No one wrote a memo yet') ?></p></div>
<?php endif ?>