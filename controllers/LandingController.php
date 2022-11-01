<?php

namespace app\controllers;

use Throwable;
use Yii;
use yii\web\Response;
use app\models\forms\LoginForm;
use yii\widgets\ActiveForm;

class LandingController extends NotSecuredController
{
    public $layout = 'landing';

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return Response|array|string
     * @throws Throwable
     */
    public function actionIndex(): Response|array|string
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $loginForm->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->redirect('/tasks');
            }
        }

        return $this->render('index', ['loginForm' => $loginForm]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->redirect('/');
    }
}
