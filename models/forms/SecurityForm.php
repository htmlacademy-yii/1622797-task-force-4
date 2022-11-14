<?php

namespace app\models\forms;

use app\models\Users;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class SecurityForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $repeatNewPassword;

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'currentPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'repeatNewPassword' => 'Повторите новый пароль'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['currentPassword', 'newPassword', 'repeatNewPassword'], 'required'],
            [['currentPassword', 'newPassword', 'repeatNewPassword'], 'string',
                'min' => 6, 'max' => 64],
            [['currentPassword'], 'validatePassword'],
            [['repeatNewPassword'], 'compare', 'compareAttribute' => 'newPassword']
        ];
    }

    /** Метод проверяет пароль на соответствие текущему
     *
     * @param $attribute
     * @return void
     */
    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = Users::findOne(['id' => Yii::$app->user->identity->id]);
            if (!$user || !Yii::$app->security->validatePassword($this->currentPassword, $user->password)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    /** Метод записывает новый пароль в БД
     *
     * @return bool
     * @throws Exception
     */
    public function changePassword(): bool
    {
        $currentUser = Yii::$app->user->identity->id;
        $user = Users::findOne($currentUser);

        if ($this->newPassword) {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPassword);
        }

        return $user->save();
    }
}
