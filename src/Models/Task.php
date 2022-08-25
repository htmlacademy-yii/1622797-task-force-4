<?php

namespace Kiipod\Taskforce\Models;

class Task
{
    // Статусы задания
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_AT_WORK = 'work';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    // Действия над заданием со стороны заказчика
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_COMPLETE = 'get done';

    // Действия над заданием со стороны исполнителя
    public const ACTION_START = 'start';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_REFUSE = 'refuse';

    public int $customerId;
    public int $executorId;
    public string $status;

    /** Функция для получения id исполнителя и id заказчика
     * @param string $status текущий статус задачи
     * @param int $customerId id заказчика
     * @param int $executorId id исполнителя
     */
    public function __construct(string $status, int $customerId, int $executorId)
    {
        $this->status = $status;
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /** Функция для возврата карты статусов
     * @return string[] возвращает массив с названием статусов
     */
    public function getStatusMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_AT_WORK => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /** Функция для возврата карты действия
     * @return string[] возвращает массив с названием действий
     */
    public function getActionMap(): array
    {
        return [
            self::ACTION_START => 'Начать',
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    /** Функция для получения доступных действия для указанного статуса задания
     * @param string $status текущий статус задания
     * @param int $userCurrentId id исполнителя/заказчика
     * @return array возвращает статус задания в зависимости от роли пользователя
     */
    public function getAvailableActions(string $status, int $userCurrentId): array
    {
        switch ($status) {
            case self::STATUS_NEW:
                if ($userCurrentId === $this->customerId) {
                    return [self::ACTION_START, self::ACTION_CANCEL];
                } elseif ($userCurrentId === $this->executorId) {
                    return [self::ACTION_RESPOND];
                }
                break;

            case self::STATUS_AT_WORK:
                if ($userCurrentId === $this->customerId) {
                    return [self::ACTION_COMPLETE];
                } elseif ($userCurrentId === $this->executorId) {
                    return [self::ACTION_REFUSE];
                }
                break;
        }
        return [];
    }

    /** Функция для получения статуса,в которой он перейдет после выполнения указанного действия
     * @param string $action текущее действие задания
     * @param string $currentStatus текущий статус задания
     * @param int $userCurrentId id исполнителя/зазказчика
     * @return string|null возвращает статус задания в зависимости от роли пользователя
     */
    public function getNextStatus(string $action, string $currentStatus, int $userCurrentId): ?string
    {
        if ($action === self::ACTION_CANCEL && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->customerId) {
            return self::STATUS_CANCELLED;
        }
        if ($action === self::ACTION_RESPOND && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->executorId) {
            return self::STATUS_AT_WORK;
        }
        if ($action === self::ACTION_COMPLETE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->customerId) {
            return self::STATUS_DONE;
        }
        if ($action === self::ACTION_REFUSE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->executorId) {
            return self::STATUS_FAILED;
        }
        return null;
    }
}
