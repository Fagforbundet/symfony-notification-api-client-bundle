<?php

namespace Fagforbundet\NotificationApiClientBundle\Client;

use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\EmailRecipient;
use Fagforbundet\NotificationApiClientBundle\Notification\Email\SentEmailMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SentSmsMessage;
use Fagforbundet\NotificationApiClientBundle\Notification\Sms\SmsMessage;

interface NotificationApiClientInterface {

  /**
   * @param EmailMessage     $emailMessage
   * @param bool             $forceUseRecipients
   * @param EmailRecipient[] $devRecipientOverrides
   *
   * @return SentEmailMessage
   */
  public function sendEmailMessage(EmailMessage $emailMessage, bool $forceUseRecipients = false, array $devRecipientOverrides = []): SentEmailMessage;

  /**
   * @param SmsMessage $smsMessage
   * @param bool       $forceUseRecipients
   * @param array      $devRecipientOverrides
   * @param bool       $allowUnicode
   * @param bool       $transliterate
   *
   * @return SentSmsMessage
   */
  public function sendSmsMessage(SmsMessage $smsMessage, bool $forceUseRecipients = false, array $devRecipientOverrides = [], bool $allowUnicode = false, bool $transliterate = false): SentSmsMessage;

}
