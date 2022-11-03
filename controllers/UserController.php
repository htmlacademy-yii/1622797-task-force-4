<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\models\Users;

class UserController extends SecuredController
{
    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $user = Users::findOne($id);
        if (!$user || !$user->is_executor) {
            throw new NotFoundHttpException("У вас нет доступа к странице пользователя с ID $id");
        }
        return $this->render('view', ['user' => $user]);
    }
}
