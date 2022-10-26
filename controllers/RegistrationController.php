<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use app\models\forms\RegistrationForm;
use taskforce\models\RegistrationUser;
use yii\web\ServerErrorHttpException;

class RegistrationController extends NotSecuredController
{
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
                return $this->redirect('/tasks');
            }
        }
        return $this->render('index', ['registrationForm' => $registrationForm]);
    }
}
