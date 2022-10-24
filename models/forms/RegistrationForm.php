<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Cities;
use app\models\Users;

class RegistrationForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $city = '';
    public string $password = '';
    public string $repeatPassword = '';
    public bool $isExecutor = false;

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'E-mail',
            'city' => 'Город',
            'password' => 'Пароль',
            'repeatPassword' => 'Повтор пароля',
            'isExecutor' => 'Я собираюсь откликаться на задания'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'repeatPassword', 'isExecutor', 'city'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            [['password', 'repeatPassword'], 'string', 'min' => 6, 'max' => 64],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'password'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => Users::class,
                'targetAttribute' => ['email' => 'email'],
                'message' => 'Пользователь с таким e-mail уже существует'],
            [['city'], 'exist', 'skipOnError' => true,
                'targetClass' => Cities::class, 'targetAttribute' => ['city' => 'id']],
            [['isExecutor'], 'boolean'],
        ];
    }
}
