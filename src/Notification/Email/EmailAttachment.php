<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Email;

final class EmailAttachment {
  private bool $inline = false;
  private $file;
  private ?string $mimeType = null;

  /**
   * EmailAttachment constructor.
   */
  public function __construct(private readonly string $filename, $file) {
    if (!\is_resource($file) && !\is_string($file)) {
      throw new \InvalidArgumentException('$file is not a resource or a string');
    }
    $this->file = $file;
  }

  /**
   * @return string
   */
  public function getFilename(): string {
    return $this->filename;
  }

  /**
   * @return resource|string
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * @return bool
   */
  public function isInline(): bool {
    return $this->inline;
  }

  /**
   * @return $this
   */
  public function asInline(): self {
    $this->inline = true;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getMimeType(): ?string {
    return $this->mimeType;
  }

  /**
   * @param string|null $mimeType
   *
   * @return $this
   */
  public function setMimeType(?string $mimeType): self {
    $this->mimeType = $mimeType;
    return $this;
  }

}
