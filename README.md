# ios-notify-php-client

This program send push notification for ios.

Example
-------
```php
<?php
require("push.class.php");

$notify = new push();
//$notify->pem_path = '/home/sckn/notify/cert.pem';
$notify->device_token = "token-here";
$notify->alert = 'seckin test';
$notify->send();
```
