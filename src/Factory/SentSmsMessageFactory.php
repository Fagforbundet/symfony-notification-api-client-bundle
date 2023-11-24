<?php

namespace Fagforbundet\NotificationApiClientBundle\Factory;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Fagforbundet\NotificationApiClientBundle\Notification\QueueName;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SentSmsMessage;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 */
final readonly class SentSmsMessageFactory {

  /**
   * @param array $payload
   *
   * @return SentSmsMessage
   */
  public function create(array $payload): SentSmsMessage {
    return new SentSmsMessage(
      Uuid::fromString($payload['uuid']),
      $payload['text'],
      $payload['textPartCount'],
      $payload['unicode'],
      (new Notification())
        ->setName($payload['notification']['name'] ?? null)
        ->setExternalReference($payload['notification']['externalReference'] ?? null)
        ->setQueueName(QueueName::from($payload['notification']['queueName']))
    );
  }

}
