<?php

namespace app\models\forms;

use app\models\Users;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class EditPasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $repeatNewPassword;
    public $showContacts;

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'currentPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'repeatNewPassword' => 'Повторите новый пароль',
            'showContacts' => 'Показывать контактные данные только заказчику'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['currentPassword'], 'required'],
            [['currentPassword', 'newPassword', 'repeatNewPassword'], 'string',
                'min' => 6, 'max' => 64],
            [['currentPassword'], 'validatePassword'],
            [['newPasswordRepeat'], 'compare', 'compareAttribute' => 'newPassword'],
            [['showContacts'], 'boolean']
        ];
    }

    /** Метод проверяет пароль на соответствие текущему
     *
     * @param $attribute
     * @return void
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = Users::findOne(['id' => Yii::$app->user->identity->id]);
            if (!$user || !$user->validatePassword($this->currentPassword)) {
                $this->addErrors($attribute, 'Неверный пароль');
            }
        }
    }

    /** Метод записывает новый пароль в БД
     *
     * @return bool
     * @throws Exception
     */
    public function changeSettings(): bool
    {
        $currentUser = Yii::$app->user->identity->id;
        $user = Users::findOne($currentUser->id);

        if ($this->newPassword) {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPassword);
        }

        $user->show_contacts = $this->showContacts;

        return $user->save();
    }
}
