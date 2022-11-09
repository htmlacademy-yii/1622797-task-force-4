<?php

namespace app\controllers;

use app\models\forms\EditProfileForm;
use Yii;
use yii\web\NotFoundHttpException;
use app\models\Users;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

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

    /**
     * @return Response|string
     * @throws ServerErrorHttpException
     */
    public function actionEdit(): Response|string
    {
        $editProfileForm = new EditProfileForm();
        $user = Users::findOne(Yii::$app->user->getId());
        $editProfileForm->autocompleteForm($editProfileForm, $user);

        if (Yii::$app->request->getIsPost()) {
            $editProfileForm->load(Yii::$app->request->post());
            $editProfileForm->avatar = UploadedFile::getInstance($editProfileForm, 'avatar');

            if ($editProfileForm->validate()) {
                $editProfileForm->setUser($user);

                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
            }
        }
        return $this->render('edit', ['editProfileForm' => $editProfileForm]);
    }
}
