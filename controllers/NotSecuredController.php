<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

class NotSecuredController extends Controller
{
    /** Метод отвечате за права доступа залогиненного пользователя к разделам сайта
     *
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return $this->redirect('/tasks');
                },
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }
}
