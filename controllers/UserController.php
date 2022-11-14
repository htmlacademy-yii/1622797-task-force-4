<?php

namespace app\controllers;

use app\models\forms\EditProfileForm;
use app\models\forms\SecurityForm;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use app\models\Users;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class UserController extends SecuredController
{
    /** Метод отвечает за показ страницы пользователя
     *
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

    /** Метод отвечает за показ страницы Редактирования профиля
     *
     * @return Response|string
     * @throws ServerErrorHttpException|Exception
     */
    public function actionEdit(): Response|string
    {
        $editProfileForm = new EditProfileForm();

        if (Yii::$app->request->getIsPost()) {
            $editProfileForm->load(Yii::$app->request->post());
            $editProfileForm->avatar = UploadedFile::getInstance($editProfileForm, 'avatar');

            if ($editProfileForm->validate()) {
                $editProfileForm->setUser();

                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
            }
        }
        return $this->render('edit', ['editProfileForm' => $editProfileForm]);
    }

    /** Метод отвечает за показ страницы Смены пароля
     *
     * @return Response|string
     * @throws \yii\base\Exception
     */
    public function actionSecurity(): Response|string
    {
        $securityForm = new SecurityForm();

        if (Yii::$app->request->getIsPost()) {
            $securityForm->load(Yii::$app->request->post());

            if ($securityForm->validate()) {
                $securityForm->changePassword();

                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
            }
        }
        return $this->render('security', ['securityForm' => $securityForm]);
    }
}
