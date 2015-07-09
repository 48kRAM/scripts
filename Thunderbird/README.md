Thunderbird scripts
============

autoconfig
---------

PHP script to implement the Mozilla Thunderbird autoconfig API. Built around the TB autoconfig docs at

https://developer.mozilla.org/en/Thunderbird/Autoconfiguration

This script should be reachable at the url `http://autoconfig.your.domain/mail/config-v1.1.xml`. You can use an apache Alias to acheive this:

      Alias /mail/config-v1.1.xml "/path/to/autoconfig/thunderbird.php"
