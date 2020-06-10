# Sms Traffic

Provider to connect with [SmsTraffic](https://www.smstraffic.ru/) service.

## Parameters

 * `login` Your system login *(required)*
 * `password` Your system password *(required)*
 * `sender` Your sender name *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        sms_traffic_provider_doc: # your custom provider name
            sms_traffic:
                login: 'your_login'
                password: 'your_secret'
                sender: 'sender'
```
