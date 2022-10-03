<?php

use app\models\Users;
use app\models\Categories;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'category_id' => $faker->randomElement(array_keys(
        Categories::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'user_id' => $faker->randomElement(array_keys(
        Users::find()->select(['id'])->asArray()->indexBy(['id'])->where(['is_executor' => true])->all()
    ))
];
