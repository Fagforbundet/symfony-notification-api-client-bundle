<?php

namespace Fagforbundet\NotificationApiClientBundle\Factory;

use Fagforbundet\NotificationApiClientBundle\Notification\Email\SentEmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Fagforbundet\NotificationApiClientBundle\Notification\QueueName;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 */
final readonly class SentEmailMessageFactory {

  /**
   * @param array $payload
   *
   * @return SentEmailMessage
   */
  public function create(array $payload): SentEmailMessage {
    return new SentEmailMessage(
      Uuid::fromString($payload['uuid']),
      $payload['subject'],
      (new Notification())
        ->setName($payload['notification']['name'] ?? null)
        ->setExternalReference($payload['notification']['externalReference'] ?? null)
        ->setQueueName(QueueName::from($payload['notification']['queueName']))
    );
  }

}
