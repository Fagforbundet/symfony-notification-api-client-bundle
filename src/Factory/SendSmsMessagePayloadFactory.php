<?php

namespace Fagforbundet\NotificationApiClientBundle\Factory;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SmsMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SmsRecipient;

/**
 * @internal
 */
final readonly class SendSmsMessagePayloadFactory {

  /**
   * @param SmsMessage $smsMessage
   *
   * @return array
   */
  public function create(SmsMessage $smsMessage): array {
    $payload = [
      'text' => $smsMessage->getText(),
      'recipients' => array_map(fn(SmsRecipient $recipient) => ['phoneNumber' => (string) $recipient], $smsMessage->getRecipients())
    ];

    if (null !== $smsMessage->getNotification()) {
      $payload['notification'] = $this->createNotificationPayload($smsMessage->getNotification());
    }

    return $payload;
  }

  /**
   * @param Notification $notification
   *
   * @return array
   */
  private function createNotificationPayload(Notification $notification): array {
    $payload = [];

    if (null !== $notification->getName()) {
      $payload['name'] = $notification->getName();
    }

    if (null !== $notification->getExternalReference()) {
      $payload['externalReference'] = $notification->getExternalReference();
    }

    if (null !== $notification->getQueueName()) {
      $payload['queueName'] = $notification->getQueueName()->value;
    }

    return $payload;
  }

}
