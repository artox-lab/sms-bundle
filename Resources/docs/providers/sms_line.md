# Sms Line

Provider to connect with [Sms Line](https://mobilemarketing.by/) service.

## Parameters

 * `login` Your system login *(required)*
 * `password` Your system password *(required)*
 * `sender` Your sender name *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        sms_line_provider_doc:
            sms_line:
                login: 'your_login'
                password: 'your_secret'
                sender: 'sender'
```
