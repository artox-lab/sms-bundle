# SMTP mail

## Parameters
 * `host` Host of mailer *(required)*
 * `port` Port of mailer *(required)*
 * `encryption` Encryption type *(default null)*
 * `username` Username
 * `password` Password
 * `sender` Sender *(required)*
 * `recipients` Recipients of message *(required)*

## Example

``` yaml
# config/packages/artox_lab_sms.yaml
artox_lab_sms:
    providers:
        mail_provider_doc: # your custom provider name
            mail:
                host: 'host'
                port: 'port'
                encryption: ''
                username: ''
                password: ''
                sender: 'sender'
                recipients: 'recipients'
```
