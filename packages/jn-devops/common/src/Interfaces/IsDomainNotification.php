<?php

namespace Homeful\Common\Interfaces;

/**
 * Interface IsDomainNotification
 *
 * This interface ensures that notification classes implement `getNotificationChannelsVia()`.
 * It allows the system to determine the correct notification channels dynamically.
 */
interface IsDomainNotification
{
    /**
     * Retrieve the notification channels for a given notifiable entity.
     *
     * @param object $notifiable The entity receiving the notification.
     * @return array The resolved notification channels.
     */
    function getNotificationChannelsVia(object $notifiable): array;
}
