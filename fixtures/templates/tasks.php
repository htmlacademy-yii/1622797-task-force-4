<?php

use app\models\Categories;
use app\models\Users;
use app\models\Cities;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->catchPhrase,
    'description' => $faker->text,
    'city_id' => $faker->randomElement(array_keys(
        Cities::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'date_creation' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
    'category_id' => $faker->randomElement(array_keys(
        Categories::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'customer_id' => $faker->randomElement(array_keys(
        Users::find()->select(['id'])->asArray()->indexBy(['id'])->where(['is_executor' => false])->all()
    )),
    'budget' => $faker->numberBetween(500, 90000),
    'period_execution' => date('Y.m.d H:i:s', $faker->dateTimeBetween('now', '++1 year')->getTimestamp())
];
