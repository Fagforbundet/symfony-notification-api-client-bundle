<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Email;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Symfony\Component\Uid\Uuid;

final readonly class SentEmailMessage {

  /**
   * SentEmailMessage constructor.
   */
  public function __construct(
    private Uuid         $uuid,
    private string       $subject,
    private Notification $notification,
  ) {
  }

  /**
   * @return Uuid
   */
  public function getUuid(): Uuid {
    return $this->uuid;
  }

  /**
   * @return string
   */
  public function getSubject(): string {
    return $this->subject;
  }

  /**
   * @return Notification
   */
  public function getNotification(): Notification {
    return $this->notification;
  }

}
