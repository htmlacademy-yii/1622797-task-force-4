<?php

namespace app\controllers;

use app\models\Cities;
use app\models\Users;
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

    public function actionAuth()
    {
        $url = Yii::$app->authClientCollection->getClient("vkontakte")->buildAuthUrl();
        Yii::$app->getResponse()->redirect($url);
    }

    public function actionVk(): Response
    {
        $code = Yii::$app->request->get('code');
        $vkClient = Yii::$app->authClientCollection->getClient("vkontakte");
        $accessToken = $vkClient->fetchAccessToken($code);
        $userAttributes = $vkClient->getUserAttributes();

        $user = Users::findOne(['vk_id' => $userAttributes['user_id']]);
        if ($user) {
            Yii::$app->user->login($user);
            return $this->redirect('/tasks');
        }
        $newUser = new Users();
        $newUser->name = $userAttributes["first_name"] . ' ' . $userAttributes["last_name"];
        $newUser->email = $userAttributes["email"];

        $city = Cities::findOne(['name' => $userAttributes["city"]['title']]);
        $newUser->city_id = $city->id;

        $newUser->is_executor = 0;
        $newUser->show_contacts = 0;
        $newUser->password = 'asasas';
        $newUser->vk_id = $userAttributes["user_id"];
        $newUser->save();
        Yii::$app->user->login($newUser);
        return $this->redirect('/tasks');
    }
}
