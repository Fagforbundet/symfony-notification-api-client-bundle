<?php

namespace Fagforbundet\NotificationApiClientBundle\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpSendMessageException extends SendMessageException {
  private ResponseInterface $response;

  /**
   * HttpSendMessageException constructor.
   */
  public function __construct(string $message, ResponseInterface $response, int $code = 0, ?\Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
    $this->response = $response;
  }

  /**
   * @return ResponseInterface
   */
  public function getResponse(): ResponseInterface {
    return $this->response;
  }

}
