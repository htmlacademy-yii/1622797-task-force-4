<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Users;

class UserController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $user = Users::findOne($id);
        if (!$user || !$user->is_executor) {
            throw new NotFoundHttpException("Пользователя с ID $id не существует");
        }
        return $this->render('view', ['user' => $user]);
    }
}
