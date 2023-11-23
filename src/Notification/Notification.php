<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification;

final class Notification {
  private ?string $name = null;
  private ?string $externalReference = null;
  private ?QueueName $queueName = null;

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
   * @return string|null
   */
  public function getExternalReference(): ?string {
    return $this->externalReference;
  }

  /**
   * @param string|null $externalReference
   *
   * @return $this
   */
  public function setExternalReference(?string $externalReference): self {
    $this->externalReference = $externalReference;
    return $this;
  }

  /**
   * @return QueueName|null
   */
  public function getQueueName(): ?QueueName {
    return $this->queueName;
  }

  /**
   * @param QueueName|null $queueName
   *
   * @return static
   */
  public function setQueueName(?QueueName $queueName): self {
    $this->queueName = $queueName;
    return $this;
  }

  /**
   * @return array
   */
  public function toPayload(): array {
    $payload = [
      'queueName' => $this->getQueueName()->value
    ];

    if (null !== $this->getName()) {
      $payload['name'] = $this->getName();
    }

    if (null !== $this->getExternalReference()) {
      $payload['externalReference'] = $this->getExternalReference();
    }

    return $payload;
  }

}
