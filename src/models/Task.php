<?php

declare(strict_types=1);

namespace taskforce\models;

use taskforce\actions\StartAction;
use taskforce\actions\CancelAction;
use taskforce\actions\CompleteAction;
use taskforce\actions\RefuseAction;
use taskforce\actions\RespondAction;
use taskforce\exception\ActionException;
use taskforce\exception\StatusException;
use taskforce\exception\ExecutorException;

class Task
{
    // Статусы задания
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_AT_WORK = 'work';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public int $customerId;
    public ?int $executorId;
    public string $status;

    /** Функция для получения id исполнителя, id заказчика и текущего статуса
     * @param string $status текущий статус задачи
     * @param int $customerId id заказчика
     * @param int|null $executorId id исполнителя
     * @throws ExecutorException выбрасывает исключение если неверная роль
     * @throws StatusException выбрасывает исключение если неверный статус
     */
    public function __construct(string $status, int $customerId, ?int $executorId)
    {
        if ($status === self::STATUS_NEW && $executorId !== null) {
            throw new StatusException();
        }
        if (in_array($status, $this->getStatusesForExecutor(), true) && $executorId === null) {
            throw new ExecutorException();
        }
        $this->status = $status;
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /** Функция возвращает статусы для исполнителя заданий
     * @return array возвращает массив с названиями статусов
     */
    public function getStatusesForExecutor(): array
    {
        return [
            self::STATUS_AT_WORK,
            self::STATUS_DONE,
            self::STATUS_FAILED
        ];
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
     * @throws ActionException выбрасывает исключение если действия не существует
     */
    public function getNextStatus(string $action): string
    {
        if (!array_key_exists($action, $this->getActionMap())) {
            throw new ActionException();
        }
        return match ($action) {
            CancelAction::class => self::STATUS_CANCELLED,
            RespondAction::class => self::STATUS_AT_WORK,
            CompleteAction::class => self::STATUS_DONE,
            RefuseAction::class => self::STATUS_FAILED,
            StartAction::class => self::STATUS_NEW,
        };
    }
}
