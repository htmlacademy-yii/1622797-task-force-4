<?php

class Task {
    // Статусы задания
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_AT_WORK = 'work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    // Действия над заданием со стороны заказчика
    const ACTION_CANCEL = 'cancel';
    const ACTION_GET_DONE = 'get done';

    // Действия над заданием со стороны исполнителя
    const ACTION_RESPOND = 'respond';
    const ACTION_REFUSE = 'refuse';

    public int $customerId;
    public int $executorId;

    /** Функция для получения id исполнителя и id заказчика
     * @param int $customerId id заказчика
     * @param int $executorId id исполнителя
     */
    public function __construct (int $customerId, int $executorId) {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /** Функция для возврата карты статусов
     * @return string[] возвращает массив с названием статусов
     */
    public function getStatusMap (): array {
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
    public function getActionMap (): array {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_GET_DONE => 'Выполнено',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    /** Функция для получения доступных действия для указанного статуса задания
     * @param string $status текущий статус задания
     * @param int $userCurrentId id исполнителя/заказчика
     * @return string|null возвращает статус задания в зависимости от роли пользователя
     */
    public function getAvailableActions (string $status, int $userCurrentId): ?string {
        if ($status === self::STATUS_NEW && $userCurrentId === $this->customerId) {
            return self::ACTION_CANCEL;
        }
        if ($status === self::STATUS_NEW && $userCurrentId === $this->executorId) {
            return self::ACTION_RESPOND;
        }
        if ($status === self::STATUS_AT_WORK && $userCurrentId === $this->customerId) {
            return self::ACTION_GET_DONE;
        }
        if ($status === self::STATUS_AT_WORK && $userCurrentId === $this->executorId) {
            return self::ACTION_REFUSE;
        }
        return null;
    }

    /** Функция для получения статуса,в которой он перейдет после выполнения указанного действия
     * @param string $action текущее действие задания
     * @param string $currentStatus текущий статус задания
     * @param int $userCurrentId id исполнителя/зазказчика
     * @return string|null возвращает статус задания в зависимости от роли пользователя
     */
    public function getNextActions (string $action, string $currentStatus, int $userCurrentId): ?string {
        if ($action === self::ACTION_CANCEL && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->customerId) {
            return self::STATUS_CANCELLED;
        }
        if ($action === self::ACTION_RESPOND && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->executorId) {
            return self::STATUS_AT_WORK;
        }
        if ($action === self::ACTION_GET_DONE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->customerId) {
            return self::STATUS_DONE;
        }
        if ($action === self::ACTION_REFUSE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->executorId) {
            return self::STATUS_FAILED;
        }
        return null;
    }
}
