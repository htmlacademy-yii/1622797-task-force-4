<?php

use app\models\Users;
use app\models\Tasks;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->randomElement(array_keys(
        Tasks::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'executor_id' => $faker->randomElement(array_keys(
        Users::find()->select(['id'])->asArray()->indexBy(['id'])->where(['is_executor' => true])->all()
    )),
    'price' => $faker->numberBetween(3000, 5000),
    'comment' => $faker->text
];
