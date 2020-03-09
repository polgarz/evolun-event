<?php
namespace evolun\event\modules\absence\assets;

use yii\web\AssetBundle;

class AbsenceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/modules/absence/assets/dist';

    public $depends = [
        'app\assets\AppAsset',
    ];

    public $js = [
        'https://unpkg.com/vue-single-select@latest',
        'absence.js',
    ];
}
