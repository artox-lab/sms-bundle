# Sms Line

Provider to connect with [LetsAds](http://letsads.com/) service.

## Parameters

 * `login` Your system login *(required)*
 * `password` Your system password *(required)*
 * `sender` Your sender name *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        lets_ads_provider_doc: # your custom provider nam
            lets_ads:
                login: 'your_login'
                password: 'your_secret'
                sender: 'sender'
```
