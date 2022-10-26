<?php

namespace app\assets;

class LandingAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/landing.css'
    ];
    public $js = [
        'js/landing.js'
    ];
}
