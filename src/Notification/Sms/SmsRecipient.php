<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification\Sms;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

final class SmsRecipient {
  public const DEFAULT_REGION = 'NO';

  /**
   * SmsRecipient constructor.
   */
  public function __construct(
    private readonly PhoneNumber $phoneNumber,
  ) {
    if (!PhoneNumberUtil::getInstance()->isValidNumber($this->phoneNumber)) {
      throw new \InvalidArgumentException('$phoneNumber is not valid phone number.');
    }
  }

  /**
   * @param string|PhoneNumber $phoneNumber
   * @param string             $defaultRegion
   *
   * @return self
   */
  public static function create(string|PhoneNumber $phoneNumber, string $defaultRegion = self::DEFAULT_REGION): self {
    if ($phoneNumber instanceof PhoneNumber) {
      return new self($phoneNumber);
    }

    try {
      return new self(PhoneNumberUtil::getInstance()->parse($phoneNumber, $defaultRegion));
    } catch (NumberParseException $e) {
      throw new \InvalidArgumentException('$phoneNumber is not valid phone number.', previous: $e);
    }
  }

  /**
   * @return PhoneNumber
   */
  public function getPhoneNumber(): PhoneNumber {
    return $this->phoneNumber;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return PhoneNumberUtil::getInstance()->format($this->getPhoneNumber(), PhoneNumberFormat::E164);
  }

}
