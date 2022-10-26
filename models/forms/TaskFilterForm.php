<?php

namespace app\models\forms;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use app\models\Tasks;

class TaskFilterForm extends Model
{
    public array $category = [];
    public string $remoteTask = '';
    public string $withoutExecutor = '';
    public string $period = '';

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'category' => 'Категории',
            'remoteTask' => 'Удаленная работа',
            'withoutExecutor' => 'Без исполнителя',
            'period' => 'Период'
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['category', 'remoteTask', 'withoutExecutor', 'period'], 'safe'],
        ];
    }

    /**
     * @return string[]
     */
    public static function getPeriodValue(): array
    {
        return [
            'all' => 'За всё время',
            'hour' => 'За час',
            'day' => 'За день',
            'week' => 'За неделю'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getNewTaskQuery(): ActiveQuery
    {
        return Tasks::find()
            ->where(['status' => Tasks::STATUS_NEW])
            ->with('category')
            ->with('city');
    }

    /**
     * @return ActiveQuery
     */
    public function getFilteredTasks(): ActiveQuery
    {
        $taskQuery = $this->getNewTaskQuery();

        if ($this->category) {
            $taskQuery->andWhere(['in', 'category_id', $this->category]);
        }
        if ($this->remoteTask) {
            $taskQuery->andWhere(['is', 'city_id', null]);
        }
        if ($this->withoutExecutor) {
            $taskQuery->andWhere(['is', 'executor_id', null]);
        }
        if ($this->period) {
            $this->chooseTaskPeriod($taskQuery);
        }
        return $taskQuery;
    }

    /**
     * @param $taskQuery
     * @return ActiveQuery
     */
    public function chooseTaskPeriod($taskQuery): ActiveQuery
    {
        switch ($this->period) {
            case 'hour':
                return $taskQuery->andWhere(['>', 'date_creation', new Expression(
                    'CURRENT_TIMESTAMP() - INTERVAL 1 HOUR'
                )
                ]);
                break;
            case 'day':
                return $taskQuery->andWhere(['>', 'date_creation', new Expression(
                    'CURRENT_TIMESTAMP() - INTERVAL 1 DAY'
                )
                ]);
                break;
            case 'week':
                return $taskQuery->andWhere(['>', 'date_creation', new Expression(
                    'CURRENT_TIMESTAMP() - INTERVAL 7 DAY'
                )
                ]);
                break;
        }
        return $taskQuery;
    }
}
