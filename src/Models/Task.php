<?php

namespace Taskforce\Models;

use Taskforce\Actions\StartAction;
use Taskforce\Actions\CancelAction;
use Taskforce\Actions\CompleteAction;
use Taskforce\Actions\RefuseAction;
use Taskforce\Actions\RespondAction;

class Task
{
    // Статусы задания
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_AT_WORK = 'work';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

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
            StartAction::class => 'Начать',
            CancelAction::class => 'Отменить',
            CompleteAction::class => 'Выполнено',
            RespondAction::class => 'Откликнуться',
            RefuseAction::class => 'Отказаться'
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
                    return [new StartAction(), new CancelAction()];
                } elseif ($userCurrentId === $this->executorId) {
                    return [new RespondAction()];
                }
                break;

            case self::STATUS_AT_WORK:
                if ($userCurrentId === $this->customerId) {
                    return [new CompleteAction()];
                } elseif ($userCurrentId === $this->executorId) {
                    return [new RefuseAction()];
                }
                break;
        }
        return [];
    }

    /** Функция для получения статуса,в которой он перейдет после выполнения указанного действия
     * @param string $action текущее действие задания
     * @return string возвращает статус задания
     */
    public function getNextStatus(string $action): string
    {
        return match ($action) {
            CancelAction::class => self::STATUS_CANCELLED,
            RespondAction::class => self::STATUS_AT_WORK,
            CompleteAction::class => self::STATUS_DONE,
            RefuseAction::class => self::STATUS_FAILED,
            StartAction::class => self::STATUS_NEW,
        };
    }
}
