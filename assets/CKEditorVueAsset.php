<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class CKEditorVueAsset extends AssetBundle
{
    public $js = [
        'https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-vue@1.0.1/dist/ckeditor.js'
    ];

    public $depends = [
        'evolun\event\assets\CKEditorAsset',
    ];
}
