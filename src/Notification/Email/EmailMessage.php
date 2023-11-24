<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Email;

use Fagforbundet\NotificationApiClientBundle\Notification\Notification;

final class EmailMessage {
  private ?string $text = null;
  private ?string $html = null;
  private array $from = [];
  private array $replyTo = [];
  private array $to = [];
  private array $cc = [];
  private array $bcc = [];
  private array $attachments = [];

  /**
   * EmailRecipient constructor.
   */
  public function __construct(
    private readonly string $subject,
    private readonly ?Notification $notification = null,
  ) {
  }

  /**
   * @return string
   */
  public function getSubject(): string {
    return $this->subject;
  }

  /**
   * @return Notification|null
   */
  public function getNotification(): ?Notification {
    return $this->notification;
  }

  /**
   * @return string|null
   */
  public function getText(): ?string {
    return $this->text;
  }

  /**
   * @param string|null $text
   */
  public function setText(?string $text): void {
    $this->text = $text;
  }

  /**
   * @return string|null
   */
  public function getHtml(): ?string {
    return $this->html;
  }

  /**
   * @param string|null $html
   */
  public function setHtml(?string $html): void {
    $this->html = $html;
  }

  /**
   * @return EmailRecipient[]
   */
  public function getFrom(): array {
    return $this->from;
  }

  /**
   * @param EmailRecipient ...$recipients
   *
   * @return self
   */
  public function addFrom(EmailRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->from, true)) {
        $this->from[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return EmailRecipient[]
   */
  public function getReplyTo(): array {
    return $this->replyTo;
  }

  /**
   * @param EmailRecipient ...$recipients
   *
   * @return $this
   */
  public function addReplyTo(EmailRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->replyTo, true)) {
        $this->replyTo[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return EmailRecipient[]
   */
  public function getTo(): array {
    return $this->to;
  }

  /**
   * @param EmailRecipient ...$recipients
   *
   * @return self
   */
  public function addTo(EmailRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->to, true)) {
        $this->to[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return EmailRecipient[]
   */
  public function getCc(): array {
    return $this->cc;
  }

  /**
   * @param EmailRecipient ...$recipients
   *
   * @return self
   */
  public function addCc(EmailRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->cc, true)) {
        $this->cc[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return EmailRecipient[]
   */
  public function getBcc(): array {
    return $this->bcc;
  }

  /**
   * @param EmailRecipient ...$recipients
   *
   * @return $this
   */
  public function addBcc(EmailRecipient ...$recipients): self {
    foreach ($recipients as $recipient) {
      if (!\in_array($recipient, $this->bcc, true)) {
        $this->bcc[] = $recipient;
      }
    }

    return $this;
  }

  /**
   * @return array
   */
  public function getAttachments(): array {
    return $this->attachments;
  }

  /**
   * @param EmailAttachment ...$attachments
   *
   * @return self
   */
  public function addAttachment(EmailAttachment ...$attachments): self {
    foreach ($attachments as $attachment) {
      if (!\in_array($attachment, $this->attachments, true)) {
        $this->attachments[] = $attachment;
      }
    }

    return $this;
  }

}
