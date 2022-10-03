<?php

use app\models\Files;
use app\models\Tasks;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->randomElement(array_keys(
        Tasks::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'file_id' => $faker->randomElement(array_keys(
        Files::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
];
