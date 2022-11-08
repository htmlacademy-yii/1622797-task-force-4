<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class NotSecuredController extends Controller
{
    /** Метод отвечает за права доступа залогиненного пользователя к разделам сайта
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
