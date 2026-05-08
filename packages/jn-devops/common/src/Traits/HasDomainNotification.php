<?php

namespace Homeful\Common\Traits;

/**
 * Trait HasDomainNotification
 *
 * Provides dynamic notification channel resolution based on the configuration.
 * Ensures that notification classes implementing `IsDomainNotification` can dynamically determine their channels.
 *
 * **How it Works:**
 * - Fetches **default channels** from `notifications.channels.default`.
 * - Fetches **specific channels** for the notification class (`self::class`).
 * - Ensures only **allowed channels** (as defined in `notifications.channels.allowed`) are used.
 *
 * **Example Configuration (`config/notifications.php`):**
 * ```php
 * return [
 *     'channels' => [
 *         'default' => ['database'],
 *         'allowed' => ['database', 'slack', 'mail'],
 *         App\Notifications\SendContactReferenceCodeNotification::class => ['mail', 'engage_spark'],
 *         App\Notifications\SendLoginMagicLinkNotification::class => ['mail', 'sms'],
 *     ],
 * ];
 * ```
 *
 * **Example Usage:**
 * ```php
 * use Homeful\Common\Traits\HasDomainNotification;
 * use Homeful\Common\Interfaces\IsDomainNotification;
 * use Illuminate\Notifications\Notification;
 *
 * class SendLoginMagicLinkNotification extends Notification implements IsDomainNotification
 * {
 *     use HasDomainNotification;
 *
 *     public function via($notifiable) {
 *         return $this->getNotificationChannelsVia($notifiable);
 *     }
 * }
 * ```
 */
trait HasDomainNotification
{
    /**
     * Determines the notification channels to use based on configuration.
     *
     * - Fetches the **default channels** (e.g., `database`).
     * - Fetches the **specific channels** for the given notification class.
     * - Filters out any channels that are **not in the allowed list**.
     *
     * @param object $notifiable The entity receiving the notification.
     * @return array The resolved notification channels.
     */
    public function getNotificationChannelsVia(object $notifiable): array
    {
//        $config = config('notifications.channels');
//
//        // Retrieve default channels
//        $defaultChannels = $config['default'] ?? [];
//
//        // Retrieve specific channels for this notification class
//        $specificChannels = $config[self::class] ?? [];
//
//        // Merge and remove duplicates
//        $mergedChannels = array_unique(array_merge($defaultChannels, $specificChannels));
//
//        // Filter out any channels that are not allowed
//        $allowedChannels = $config['allowed'] ?? [];
//
//        return array_intersect($mergedChannels, $allowedChannels);

        logger(config('notifications.channels.allowed'));
        logger(config('notifications.channels.default'));
        logger(self::class);
        logger(config('notifications.channels')[static::class]);
        $channels = array_intersect(array_unique(array_merge(config('notifications.channels.default'), config('notifications.channels')[static::class])), config('notifications.channels.allowed'));
        logger('HasDomainNotification@getNotificationChannelsVia');
        logger($channels);

        return $channels;
    }
}
