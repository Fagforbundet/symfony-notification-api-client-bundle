<?php

namespace Fagforbundet\NotificationApiClientBundle\Notification;

enum QueueName : string {
  case PRIORITY_10000 = 'priority_10000';
  case PRIORITY_20000 = 'priority_20000';
  case PRIORITY_30000 = 'priority_30000';
  case PRIORITY_40000 = 'priority_40000';
  case PRIORITY_50000 = 'priority_50000';
  case PRIORITY_60000 = 'priority_60000';
}
