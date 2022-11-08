<?php

namespace taskforce\models;

use Yii;
use app\models\Users;
use yii\base\Exception;
use app\models\forms\RegistrationForm;

class RegistrationUser
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

        return $user->save(false);
    }
}
