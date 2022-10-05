<?php

use app\models\Cities;
use app\models\Files;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->firstName,
    'email' => $faker->email,
    'password' => $faker->password,
    'city_id' => $faker->randomElement(array_keys(
        Cities::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'date_creation' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
    'avatar_file_id' => $faker->randomElement(array_keys(
        Files::find()->select(['id'])->asArray()->indexBy(['id'])->all()
    )),
    'birthday' => date('Y.m.d H:i:s', $faker->dateTimeBetween->getTimestamp()),
    'phone' => substr($faker->e164PhoneNumber, 1, 11),
    'bio' => $faker->text,
    'status' => 'new',
    'is_executor' => $faker->boolean
];
