<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

class TasksController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $taskList = Tasks::find()
            ->where(['status' => Tasks::STATUS_NEW])
            ->with('category')
            ->with('city');

        $dataProvider = new ActiveDataProvider([
            'query' => $taskList,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_creation' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}
