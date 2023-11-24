<?php

namespace Fagforbundet\NotificationApiClientBundle\Factory;

use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailRecipient;
use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 */
final readonly class SendEmailMessagePayloadFactory {

  /**
   * @param EmailMessage $emailMessage
   * @param Uuid[]       $attachmentUuids
   *
   * @return array
   */
  public function create(EmailMessage $emailMessage, array $attachmentUuids = []): array {
    $payload = [
      'subject' => $emailMessage->getSubject()
    ];

    if (null !== $emailMessage->getNotification()) {
      $payload['notification'] = $this->createNotificationPayload($emailMessage->getNotification());
    }

    if (null !== $emailMessage->getText()) {
      $payload['text'] = $emailMessage->getText();
    }

    if (null !== $emailMessage->getHtml()) {
      $payload['html'] = $emailMessage->getHtml();
    }

    $payload['from'] = \array_map($this->createRecipientPayload(...), $emailMessage->getFrom());
    $payload['replyTo'] = \array_map($this->createRecipientPayload(...), $emailMessage->getReplyTo());
    $payload['to'] = \array_map($this->createRecipientPayload(...), $emailMessage->getTo());
    $payload['cc'] = \array_map($this->createRecipientPayload(...), $emailMessage->getCc());
    $payload['bcc'] = \array_map($this->createRecipientPayload(...), $emailMessage->getBcc());
    $payload['attachmentUuids'] = \array_map(\strval(...), $attachmentUuids);

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

  /**
   * @param EmailRecipient $emailRecipient
   *
   * @return array
   */
  private function createRecipientPayload(EmailRecipient $emailRecipient): array {
    $payload = [
      'emailAddress' => $emailRecipient->getEmailAddress()
    ];

    if (null !== $emailRecipient->getName()) {
      $payload['name'] = $emailRecipient->getName();
    }

    return $payload;
  }



}
