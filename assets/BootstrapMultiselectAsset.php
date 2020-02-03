<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class BootstrapMultiselectAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-multiselect/dist';

    public $css = [
        'css/bootstrap-multiselect.css'
    ];

    public $js = [
        'js/bootstrap-multiselect.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
