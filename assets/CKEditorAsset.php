<?php
namespace evolun\event\assets;

use yii\web\AssetBundle;

class CKEditorAsset extends AssetBundle
{
    public $sourcePath = '@npm/ckeditor--ckeditor5-build-classic';

    public $js = [
        'build/ckeditor.js'
    ];
}
