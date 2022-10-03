<?php

namespace app\models;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $city_id
 * @property string $date_creation
 * @property int|null $rating
 * @property int|null $popularity
 * @property int|null $avatar_file_id
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $bio
 * @property int|null $orders_num
 * @property int $status
 * @property int $is_executor
 *
 * @property Files $avatarFile
 * @property Cities $city
 * @property ExecutorCategory[] $executorCategories
 * @property Feedback[] $feedbacks
 * @property Feedback[] $feedbacks0
 * @property Response[] $responses
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'city_id', 'status', 'is_executor'], 'required'],
            [['city_id', 'rating', 'popularity', 'avatar_file_id', 'orders_num', 'status', 'is_executor'], 'integer'],
            [['date_creation', 'birthday'], 'safe'],
            [['bio'], 'string'],
            [['name', 'email'], 'string', 'max' => 255],
            [['password', 'telegram'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['avatar_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class,
                'targetAttribute' => ['avatar_file_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class,
                'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'city_id' => 'City ID',
            'date_creation' => 'Date Creation',
            'rating' => 'Rating',
            'popularity' => 'Popularity',
            'avatar_file_id' => 'Avatar File ID',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'bio' => 'Bio',
            'orders_num' => 'Orders Num',
            'status' => 'Status',
            'is_executor' => 'Is Executor',
        ];
    }

    /**
     * Gets query for [[AvatarFile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarFile()
    {
        return $this->hasOne(Files::class, ['id' => 'avatar_file_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[ExecutorCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorCategories()
    {
        return $this->hasMany(ExecutorCategory::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks0()
    {
        return $this->hasMany(Feedback::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id']);
    }
}
