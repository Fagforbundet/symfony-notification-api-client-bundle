<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Sms;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;
use Symfony\Component\Uid\Uuid;

final readonly class SentSmsMessage {

  /**
   * SentSmsMessage constructor.
   */
  public function __construct(
    private Uuid $uuid,
    private string $text,
    private int $textPartCount,
    private bool $unicode,
    private Notification $notification
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
  public function getText(): string {
    return $this->text;
  }

  /**
   * @return int
   */
  public function getTextPartCount(): int {
    return $this->textPartCount;
  }

  /**
   * @return bool
   */
  public function isUnicode(): bool {
    return $this->unicode;
  }

  /**
   * @return Notification
   */
  public function getNotification(): Notification {
    return $this->notification;
  }

}
