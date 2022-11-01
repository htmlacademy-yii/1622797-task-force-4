<?php

echo Yii::$app->response->sendFile(Yii::getAlias('@webroot/uploads/') . $path)->send();
