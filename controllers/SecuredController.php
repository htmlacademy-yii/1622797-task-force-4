<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class SecuredController extends Controller
{
    /** Метод отвечате за права доступа незалогиненного пользователя к разделам сайта
     *
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return $this->redirect('/');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
}
