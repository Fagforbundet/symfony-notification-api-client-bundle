<?php

namespace Fagforbundet\NotificationApiClientBundle\Client;

use Fagforbundet\NotificationApiClientBundle\Exception\HttpSendMessageException;
use Fagforbundet\NotificationApiClientBundle\Exception\SendMessageException;
use Fagforbundet\NotificationApiClientBundle\Factory\SendEmailMessagePayloadFactory;
use Fagforbundet\NotificationApiClientBundle\Factory\SendSmsMessagePayloadFactory;
use Fagforbundet\NotificationApiClientBundle\Factory\SentEmailMessageFactory;
use Fagforbundet\NotificationApiClientBundle\Factory\SentSmsMessageFactory;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailAttachment;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\SentEmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SentSmsMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SmsMessage;
use HalloVerden\Oidc\ClientBundle\Interfaces\ClientCredentialsTokenProviderServiceInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NotificationApiClient implements NotificationApiClientInterface {
  public const DEFAULT_BASE_URI = 'https://api.meldinger.fagforbundet.dev';

  private const SEND_EMAIL_ENDPOINT = '/v1/notifications/email';
  private const UPLOAD_EMAIL_ATTACHMENT_ENDPOINT = '/v1/notifications/email/attachments';
  private const SEND_SMS_ENDPOINT = '/v1/notifications/sms';

  private const HEADER_FORCE_USE_RECIPIENTS = 'X-Force-Use-Recipients';
  private const HEADER_DEV_RECIPIENT_OVERRIDE = 'X-Dev-Recipient-Override';
  private const HEADER_DEV_RECIPIENT_OVERRIDES = 'X-Dev-Recipient-Overrides';

  private HttpClientInterface $client;
  private SendEmailMessagePayloadFactory $sendEmailMessagePayloadFactory;
  private SendSmsMessagePayloadFactory $sendSmsMessagePayloadFactory;
  private SentEmailMessageFactory $sentEmailMessageFactory;
  private SentSmsMessageFactory $sentSmsMessageFactory;

  /**
   * NotificationApiClient constructor.
   */
  public function __construct(
    private readonly ClientCredentialsTokenProviderServiceInterface $clientCredentialsTokenProviderService,
    HttpClientInterface $client = null,
    string $baseUri = self::DEFAULT_BASE_URI
  ) {
    $options = ['base_uri' => $baseUri];
    $this->client = $client?->withOptions($options) ?? HttpClient::create($options);
    $this->sendEmailMessagePayloadFactory = new SendEmailMessagePayloadFactory();
    $this->sendSmsMessagePayloadFactory = new SendSmsMessagePayloadFactory();
    $this->sentEmailMessageFactory = new SentEmailMessageFactory();
    $this->sentSmsMessageFactory = new SentSmsMessageFactory();
  }

  /**
   * @inheritDoc
   */
  public function sendEmailMessage(EmailMessage $emailMessage, bool $forceUseRecipients = false, array $devRecipientOverrides = []): SentEmailMessage {
    $headers = [];

    if ($forceUseRecipients) {
      $headers[self::HEADER_FORCE_USE_RECIPIENTS] = 'true';
    }

    if ($devRecipientOverrides) {
      $headers[self::HEADER_DEV_RECIPIENT_OVERRIDE] = \array_map(\strval(...), $devRecipientOverrides);
    }

    try {
      $response = $this->client->request(Request::METHOD_POST, self::SEND_EMAIL_ENDPOINT, [
        'headers' => $headers,
        'json' => [
          'email' => $this->sendEmailMessagePayloadFactory->create($emailMessage, $this->uploadEmailAttachments($emailMessage->getAttachments()))
        ],
        'auth_bearer' => $this->clientCredentialsTokenProviderService->getToken(),
      ]);
    } catch (TransportExceptionInterface $e) {
      throw new SendMessageException('Unable to send an email: Could not reach notification-api.', previous: $e);
    }

    try {
      $result = $response->toArray();
    } catch (ExceptionInterface $e) {
      throw new HttpSendMessageException(\sprintf('Unable to send an email: got (%d) from notification-api', $e->getCode()), $response, previous: $e);
    }

    return $this->sentEmailMessageFactory->create($result['email']);
  }

  /**
   * @inheritDoc
   */
  public function sendSmsMessage(SmsMessage $smsMessage, bool $forceUseRecipients = false, array $devRecipientOverrides = [], bool $allowUnicode = false, bool $transliterate = false): SentSmsMessage {
    $headers = [];

    if ($forceUseRecipients) {
      $headers[self::HEADER_FORCE_USE_RECIPIENTS] = 'true';
    }

    if ($devRecipientOverrides) {
      $headers[self::HEADER_DEV_RECIPIENT_OVERRIDES] = \array_map(\strval(...), $devRecipientOverrides);
    }

    try {
      $response = $this->client->request(Request::METHOD_POST, self::SEND_SMS_ENDPOINT, [
        'headers' => $headers,
        'json' => [
          'sms' => $this->sendSmsMessagePayloadFactory->create($smsMessage),
          'allowUnicode' => $allowUnicode,
          'transliterate' => $transliterate
        ],
        'auth_bearer' => $this->clientCredentialsTokenProviderService->getToken(),
      ]);
    } catch (TransportExceptionInterface $e) {
      throw new SendMessageException('Unable to send an email: Could not reach notification-api.', previous: $e);
    }

    try {
      $result = $response->toArray();
    } catch (ExceptionInterface $e) {
      throw new HttpSendMessageException(\sprintf('Unable to send an email: got (%d) from notification-api', $e->getCode()), $response, previous: $e);
    }

    return $this->sentSmsMessageFactory->create($result['sms']);
  }

  /**
   * @param EmailAttachment[] $emailAttachments
   *
   * @return Uuid[]
   */
  private function uploadEmailAttachments(array $emailAttachments): array {
    $uuids = [];

    foreach ($emailAttachments as $emailAttachment) {
      $uuids[] = $this->uploadEmailAttachment($emailAttachment);
    }

    return $uuids;
  }

  /**
   * @param EmailAttachment $emailAttachment
   *
   * @return Uuid
   */
  private function uploadEmailAttachment(EmailAttachment $emailAttachment): Uuid {
    try {
      $response = $this->client->request(Request::METHOD_POST, self::UPLOAD_EMAIL_ATTACHMENT_ENDPOINT, [
        'headers' => [
          'Content-Type' => $emailAttachment->getMimeType()
        ],
        'body' => $emailAttachment->getFile(),
        'query' => [
          'filename' => $emailAttachment->getFilename(),
          'inline' => $emailAttachment->isInline() ? 'true' : 'false'
        ],
        'auth_bearer' => $this->clientCredentialsTokenProviderService->getToken()
      ]);
    } catch (TransportExceptionInterface $e) {
      throw new SendMessageException('Unable to send an email attachment: Could not reach notification-api.', previous: $e);
    }

    try {
      $result = $response->toArray();
    } catch (ExceptionInterface $e) {
      throw new HttpSendMessageException(\sprintf('Unable to send an email attachment: got (%d) from notification-api', $e->getCode()), $response, previous: $e);
    }

    return Uuid::fromString($result['attachment']['uuid']);
  }

}
