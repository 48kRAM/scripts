<?php

# Thunderbird autoconfig API back-end for PHP
#
# Copyright 2015 Joshua Malone <jmalone@nrao.edu>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.

# This script is based on the Mozilla Thunderbird autoconfigration API
# documented at:
#
# https://developer.mozilla.org/en/Thunderbird/Autoconfiguration
# https://developer.mozilla.org/en-US/docs/Mozilla/Thunderbird/Autoconfiguration/FileFormat/HowTo

# Domain for which you are serving autoconfig info
$yourdomain="example.com";

# Display names for the auto-configured email account
$displayname="Example, Inc. E-mail";
$shortname="Example"


### IMAP server
# If your mail is not all hosted on the same server (or at least
# the same DNS name) you will have to insert your own logic to
# map your usernames to the correct server.
# Also, the XML output assumes IMAP. If you need to support POP3
# accounts, refer to Mozilla's documentation to change the XML below.
$imapserver="imap.example.com";
$imapport=993;
# Valid options are SSL, STARTTLS, plain
$imapsecurity="SSL";
# Valid options are:
#   password-cleartext (Cleartext - use SSL/TLS with this)
#   password-encrypted (CRAM-MD5)
$imapauth="password-cleartext";

### SMTP server
# Define the DNS name, port and protocol your clients should use.
# Again, you may need to insert your own logic to map clients to
# different servers (load balancing, etc.)
$smtpserver="smtp.example.com";
# Likely ports are 25, 587, 465
$smtpport=587;
# Valid options are SSL, STARTTLS, plain
$smtpsecurity="STARTTLS";
# Valid options are:
#   password-cleartext (Cleartext - use SSL/TLS with this)
#   password-encrypted (CRAM-MD5)
#   client-IP-address (No auth required within company network)
$smtpauth="password-cleartext";


##############  Code below  ###################

# Get the email address and username from the API request
$email=html_entity_decode($_REQUEST['emailaddress']);
$username=rtrim(substr($email, 0, strpos($email, '@')));
# Get domain
$domain=ltrim(substr($email,strpos($email,'@')+1));

#####  Additional Logic  #####

# If you need to direct specific users to specific mail servers,
# implement your routing logic here.

#####  End Additional Logic  #####

### Spit out the proper autoconfig XML file
$xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>

<clientConfig version="1.1">
  <emailProvider id="$yourdomain">
    <domain>$yourdomain</domain>
    <displayName>$displayname</displayName>
    <displayShortName>$shortname</displayShortName>
    <incomingServer type="imap">
      <hostname>$imapserver</hostname>
      <port>$imapport</port>
      <socketType>$imapsecurity</socketType>
      <authentication>$imapauth</authentication>
      <username>$username</username>
    </incomingServer>
    <outgoingServer type="smtp">
      <hostname>$smtpserver</hostname>
      <port>$smtpport</port>
      <socketType>$smtpsecurity</socketType>
      <authentication>$smtpauth</authentication>
      <username>$username</username>
    </outgoingServer>
  </emailProvider>
</clientConfig>

EOF;

header('content-type:application/xml;charset=utf-8');
print $xml;

?>
