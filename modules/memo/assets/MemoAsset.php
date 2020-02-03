<?php
namespace evolun\event\modules\memo\assets;

use yii\web\AssetBundle;

class MemoAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/modules/memo/assets/dist';

    public $depends = [
        'app\assets\AppAsset',
        'evolun\event\assets\CKEditorVueAsset',
    ];

    public $jsOptions = [
        'type' => 'module'
    ];

    public $js = [
        'memo.js'
    ];
}
