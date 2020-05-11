# Infobip

Provider to connect with [Infobip](https://www.infobip.com/) service.

## Parameters

 * `login` Your system login *(required)*
 * `password` Your system password *(required)*
 * `sender` Your sender name *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        infobip_provider_doc: # your custom provider name
            infobip:
                login: 'your_login'
                password: 'your_secret'
                sender: 'sender'
```
