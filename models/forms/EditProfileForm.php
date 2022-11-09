<?php

namespace app\models\forms;

use app\models\Categories;
use app\models\Files;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
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
            'category' => 'Выбор специализаций'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['email'], 'email'],
            [['bio'], 'string'],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            ['category', 'exist', 'targetClass' => Categories::class,
                'targetAttribute' => ['category' => 'id']],
            [['avatar'], 'file', 'checkExtensionByMimeType' => true,
                'extensions' => 'jpg, png',
                'wrongExtension' => 'Только форматы jpg и png',
                'targetClass' => Files::class,
                'targetAttribute' => ['avatar_file_id' => 'id']]
            ];
    }

    /**
     * @return array|ActiveRecord|null
     */
    public function getUser(): array|ActiveRecord|null
    {
        return Users::find()
            ->where(['id' => Yii::$app->user->identity->id])->one();
    }

    /**
     * @param $form
     * @param $user
     * @return void
     */
    public function autocompleteForm($form, $user): void
    {
        $form->avatar = Yii::$app->user->identity->avatar_file_id;
        $form->name = Yii::$app->user->identity->name;
        $form->email = Yii::$app->user->identity->email;
        $form->birthday = Yii::$app->user->identity->birthday;
        $form->phone = Yii::$app->user->identity->phone;
        $form->telegram = Yii::$app->user->identity->telegram;
        $form->bio = Yii::$app->user->identity->bio;
        $form->category = $user->category_id;
    }

    /**
     * @param $user
     * @return void
     * @throws ServerErrorHttpException
     */
    public function setUser($user): void
    {
        if (!$this->uploadAvatar($user) && $this->avatar) {
            throw new ServerErrorHttpException('Загрузить файл не удалось');
        }
        $user->name = $this->name;
        $user->email = $this->email;
        $user->birthday = $this->birthday;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->bio = $this->bio;
        $user->executorCategories = $this->category;
        $user->save();
    }

    /**
     * @param $user
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function uploadAvatar($user): bool
    {
        if ($this->validate() && $this->avatar) {
            $avatarName = uniqid('avatar') . '.' . $this->avatar->getExtension();
            $user->avatar_file_id = $avatarName;

            if (!$this->avatar->saveAs('@webroot/uploads/avatar' . $avatarName)) {
                throw new ServerErrorHttpException('Ошибка загрузки аватара');
            }
            return true;
        }
        return false;
    }
}
