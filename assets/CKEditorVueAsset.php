<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class CKEditorVueAsset extends AssetBundle
{
    public $sourcePath = '@npm/ckeditor--ckeditor5-vue';

    public $js = [
        'dist/ckeditor.js'
    ];

    public $depends = [
        'evolun\event\assets\CKEditorAsset',
    ];
}
