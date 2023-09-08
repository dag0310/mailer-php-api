# Mailer - PHP API

Send emails using an HTTP PHP API using PHPMailer.

## Installation

- Just copy the `www` folder to your webserver.
- Make sure to rename `config.template.ini` to `config.ini` and set the fields.
- Make sure `config.ini` is inaccessible through the web, .htaccess does this already for Apache servers.

## Usage examples

### PHP
```php
$headers = [
    'Content-type: application/x-www-form-urlencoded',
    "Authorization: Bearer API_TOKEN_GOES_HERE",
];

$data = http_build_query([
    'to_email': 'john.doe@example.com',
    'subject': 'Test email',
    'message_html': 'HTML message body',
]);

$context = stream_context_create([
  'http' => [
      'method' => 'POST',
      'header' => implode("\r\n", $headers),
      'content' => $data,
  ],
]);

$response = file_get_contents('https://example.com/mailer/', FALSE, $context);

if ($response === FALSE) {
  die('Error: Unable to send the request.');
}
```

### JavaScript

```javascript
const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    Authorization: 'Bearer API_TOKEN_GOES_HERE'
  },
  body: new URLSearchParams({
    to_email: 'john.doe@example.com',
    subject: 'Test email',
    message_html: 'HTML message body',
  })
};

fetch('https://example.com/mailer/', options)
  .then(response => response.json())
  .then(response => console.log(response))
  .catch(err => console.error(err));
```

### Python

```python
import http.client

conn = http.client.HTTPSConnection("example.com")

payload = "to_email=john.doe@example.com&subject=Test%20email&message_html=HTML%20message%20body"

headers = {
    'Content-Type': "application/x-www-form-urlencoded",
    'Authorization': "Bearer API_TOKEN_GOES_HERE"
    }

conn.request("POST", "/mailer/", payload, headers)

res = conn.getresponse()
data = res.read()

print(data.decode("utf-8"))
```
