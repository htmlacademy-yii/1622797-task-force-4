<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use app\models\forms\RegistrationForm;
use taskforce\models\RegistrationUser;
use yii\web\ServerErrorHttpException;

class RegistrationController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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

    /**
     * @return Response|string
     * @throws Exception
     */
    public function actionIndex(): Response|string
    {
        $registrationForm = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $registrationForm->load(Yii::$app->request->post());
            if ($registrationForm->validate()) {
                if (!RegistrationUser::registration($registrationForm)) {
                    throw new ServerErrorHttpException(
                        'Не удалось сохранить данные, попробуйте попытку позже'
                    );
                }
                return $this->redirect(['tasks/index']);
            }
        }
        return $this->render('index', ['registrationForm' => $registrationForm]);
    }
}
