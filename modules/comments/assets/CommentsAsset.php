<?php
namespace evolun\event\modules\comments\assets;

use yii\web\AssetBundle;

class CommentsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/modules/comments/assets/dist';

    public $depends = [
        'app\assets\AppAsset',
    ];

    public $js = [
        'comments.js'
    ];
}
