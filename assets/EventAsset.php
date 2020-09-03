<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class EventAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/assets/dist';

    public $js = ['js/event.js'];
    public $css = ['css/event.css'];

    public $depends = [
        'evolun\event\assets\BootstrapMultiselectAsset',
        'evolun\event\assets\SummernoteAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
