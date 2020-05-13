# Infobip

Provider to connect with [Infobip](https://www.infobip.com/) service.

## Parameters

 * `token` Your API key *(required)*
 * `sender` Your sender name *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        infobip_provider_doc: # your custom provider name
            infobip:
                token: 'api_key'
                sender: 'sender'
```
