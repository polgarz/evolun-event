<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class EventAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/assets';

    public $js = ['dist/js/event.js'];

    public $depends = [
        'evolun\event\assets\BootstrapMultiselectAsset',
        'evolun\event\assets\SummernoteAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
