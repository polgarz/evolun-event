<?php
namespace evolun\event\modules\participates\assets;

use yii\web\AssetBundle;

class ParticipatesAsset extends AssetBundle
{
    public $sourcePath = '@vendor/polgarz/evolun-event/modules/participates/assets/dist';

    public $depends = [
        'app\assets\AppAsset',
    ];

    public $js = [
        'participates.js'
    ];
}
