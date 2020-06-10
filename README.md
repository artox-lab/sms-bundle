# Sms Bundle

This bundle will help you to implement an sms messages to your project.

Based on [yamilovs/sms-bundle](https://github.com/yamilovs/SmsBundle)

# Installation 

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require artox-lab/sms-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

``` bash
$ composer require artox-lab/sms-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    ArtoxLab\Bundle\SmsBundle\ArtoxLabSmsBundle::class => ['all' => true],
];
```

### Step 3: Configuration

You can define as many provider configurations as you want. Available providers are:
 
 * [SmsLine](Resources/docs/providers/sms_line.md) [mobilemarketing.by]
 * [LetsAds](Resources/docs/providers/lets_ads.md) [letsads.com]
 * [InfoBip](Resources/docs/providers/infobip.md) [infobip.com]
 * [SmsTraffic](Resources/docs/providers/sms_traffic.md) [smstraffic.ru]

For debug:
 * [Log](Resources/docs/providers/log.md)
 * [Mail](Resources/docs/providers/mail.md)
 * [Slack](Resources/docs/providers/slack.md)
 
 
# Usage

#### In your controller

```php
<?php
// src/Controller/FooController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ArtoxLab\Bundle\SmsBundle\Service\ProviderManager;
use ArtoxLab\Bundle\SmsBundle\Sms\Sms;

class FooController extends Controller
{
    public function barAction(ProviderManager $providerManager)
    {
        $sms = new Sms('+12345678900', 'The cake is a lie');
        $provider = $providerManager->getProvider('your_provider_name');
        
        $provider->send($sms);
    }
}
```
