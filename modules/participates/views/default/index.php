<?php
use evolun\event\modules\participates\assets\ParticipatesAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

$this->registerJsVar('participatesListUrl', Url::to(['participates', 'id' => $event->id]));
$this->registerJsVar('participateDeleteUrl', Url::to(['delete', 'id' => $event->id]));

ParticipatesAsset::register($this);
?>
<div id="participates">
    <div class="alert alert-danger alert-dissmissible" v-if="errors.length" v-cloak>
        <h4><i class="icon fa fa-ban"></i> Hiba!</h4>
        <div v-for="error in errors">{{error}}</div>
    </div>

    <div v-for="(item, role) in data.items" v-cloak v-if="data.items">
        <h4 v-if="role.length > 0">{{role}} ({{item.length}} fő)</h4>
        <table class="table table-hover">
            <tr v-for="participate in item">
                <td>
                    <?php if (Yii::$app->user->can('showUsers')): ?>
                        <a :href="participate.url" class="text-default">
                    <?php endif ?>
                    <div class="media">
                        <div class="media-left media-middle">
                            <img :src="participate.image" class="img-circle" width="40">
                        </div>
                        <div class="media-body">
                            <strong>{{participate.name}} ({{participate.nickname}})</strong>
                            <div class="text-muted">
                                {{participate.summary}}
                            </div>
                            <div class="text-muted" v-if="!participate.whole">
                                <strong>
                                    <span v-if="participate.days.length">
                                        {{participate.days}}
                                    </span>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <?php if (Yii::$app->user->can('showUsers')): ?>
                        </a>
                    <?php endif ?>
                </td>
                <?php if (Yii::$app->user->can('manageEvents', ['event' => $event])): ?>
                    <td style="vertical-align: middle">
                        <button class="btn btn-danger btn-sm" v-on:click="deleteParticipate(participate.user_id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                <?php endif ?>
            </tr>
        </table>

    </div>


    <div class="text-muted" v-if="data.items && data.items.length == 0" v-cloak><p>Nincsenek résztvevők</p></div>

</div>

