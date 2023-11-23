<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Email;

final class EmailRecipient {
  private ?string $name = null;

  /**
   * EmailRecipient constructor.
   */
  public function __construct(private readonly string $emailAddress) {
  }

  /**
   * @return string
   */
  public function getEmailAddress(): string {
    return $this->emailAddress;
  }

  /**
   * @return string|null
   */
  public function getName(): ?string {
    return $this->name;
  }

  /**
   * @param string|null $name
   *
   * @return $this
   */
  public function setName(?string $name): self {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    if (null === $this->getName()) {
      return $this->getEmailAddress();
    }

    return $this->getName() . '<' . $this->getEmailAddress() . '>';
  }

}
