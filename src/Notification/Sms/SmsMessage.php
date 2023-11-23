<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Sms;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;

final class SmsMessage {

  /**
   * Sms constructor.
   */
  public function __construct(
    private readonly string        $text,
    private array                  $recipients = [],
    private readonly ?Notification $notification = null,
  ) {
    $this->addRecipient(...$recipients);
  }

  /**
   * @return string
   */
  public function getText(): string {
    return $this->text;
  }

  /**
   * @return SmsRecipient[]
   */
  public function getRecipients(): array {
    return $this->recipients;
  }

  /**
   * @param SmsRecipient ...$recipients
   *
   * @return $this
   */
  public function addRecipient(SmsRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->recipients, true)) {
        $this->recipients[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return Notification|null
   */
  public function getNotification(): ?Notification {
    return $this->notification;
  }

}
