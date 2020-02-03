<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class SummernoteAsset extends AssetBundle
{
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-hu-HU.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
