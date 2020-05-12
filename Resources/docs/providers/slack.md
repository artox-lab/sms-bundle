# Slack

## Parameters

 * `token` Authentication token bearing required scopes *(required)*
 * `channel` Channel, private group, or IM channel to send message to *(required)*
 
## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        slack_provider_doc: # your custom provider name
            slack:
                token: 'token'
                channel: 'channel'
```
