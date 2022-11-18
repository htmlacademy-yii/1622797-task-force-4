<?php

namespace app\models\forms;

use app\models\Categories;
use app\models\ExecutorCategory;
use app\models\Files;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

class EditProfileForm extends Model
{
    public $avatar;
    public $name;
    public $email;
    public $birthday;
    public $phone;
    public $telegram;
    public $bio;
    public $category;
    public $showContacts;

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'avatar' => 'Аватар',
            'name' => 'Ваше имя',
            'email' => 'E-mail',
            'birthday' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'bio' => 'Информация о себе',
            'category' => 'Выбор специализаций',
            'showContacts' => 'Показывать мои контакты только заказчикам, по заданиям которых работаю'
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['name'], 'string'],
            [['email'], 'email'],
            [['avatar'], 'file', 'skipOnEmpty' => true,
                'extensions' => 'png, jpg, jpeg', 'maxSize' => 5 * 1024 * 1024],
            [['avatar', 'birthday', 'phone', 'telegram', 'bio', 'category'],
                'default', 'value' => null],
            [['phone'], 'match', 'pattern' => '/^[\d]{11}/i'],
            [['telegram'], 'string', 'max' => 64],
            [['telegram'], 'match', 'pattern' => '/^@\w*$/i',
                'message' => 'Телеграм должен начинаться со знака @'],
            [['bio'], 'string', 'max' => 2000],
            ['showContacts', 'boolean']
        ];
    }

    /**
     * @return void
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function setUser(): void
    {
        $user = Users::findOne(Yii::$app->user->getId());
        $user->name = $this->name;
        $user->email = $this->email;
        $user->birthday = $this->birthday;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->bio = $this->bio;

        if ($this->avatar) {
            $path = '/uploads/avatar/' . uniqid('avatar') . '.' . $this->avatar->getExtension();

            if ($this->uploadAvatar($path)) {
                $user->avatar = $path;
            }
        }

        if ($this->category) {
            ExecutorCategory::deleteExecutorCategories();

            foreach ($this->category as $category) {
                $userCategory = new ExecutorCategory();
                $userCategory->user_id = Yii::$app->user->getId();
                $userCategory->category_id = $category;
                $userCategory->save(false);
            }
        }

        if (!empty($user->showContacts)) {
            $user->show_contacts = $this->showContacts;
        }

        $user->save(false);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function uploadAvatar(string $path): bool
    {
        if ($this->validate()) {
            $this->avatar->saveAs('@webroot' . $path);

            return true;
        }
        return false;
    }
}
