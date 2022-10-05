<?php

use app\models\Users;
use app\models\Tasks;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'customer_id' => $faker->randomElement(array_keys(
        Users::find()->select(['id'])->asArray()->indexBy(['id'])->where(['is_executor' => false])->all()
    )),
    'executor_id' => $faker->randomElement(array_keys(
        Users::find()->select(['id'])->asArray()->indexBy(['id'])->where(['is_executor' => true])->all()
    )),
    'task_id' => $faker->randomElement(array_keys(
        Tasks::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'date_creation' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
    'description' => $faker->text,
    'rating' => $faker->numberBetween(0, 5)
];
