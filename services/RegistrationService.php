<?php

namespace app\services;

use app\models\Cities;
use app\models\forms\RegistrationForm;
use app\models\Users;
use Yii;
use yii\base\Exception;

class RegistrationService
{
    /** Метод сохраняет данные введенные при регистрации в БД
     *
     * @throws Exception
     */
    public static function registration(RegistrationForm $form): ?bool
    {
        $user = new Users();
        $user->name = $form->name;
        $user->email = $form->email;
        $user->city_id = $form->city;
        $user->password = Yii::$app->security->generatePasswordHash($form->password);
        $user->is_executor = $form->isExecutor;
        $user->show_contacts = 0;

        return $user->save(false);
    }

    /**
     * @throws Exception
     */
    public function createVk($userAttributes): Users
    {
        $newUser = new Users();
        $newUser->name = $userAttributes["first_name"] . ' ' . $userAttributes["last_name"];
        $newUser->email = $userAttributes["email"];

        $city = Cities::findOne(['name' => $userAttributes["city"]['title']]);
        $newUser->city_id = $city->id;

        $newUser->password = Yii::$app->getSecurity()->generatePasswordHash('password');
        $newUser->vk_id = $userAttributes["user_id"];
        $newUser->save();

        return $newUser;
    }
}
