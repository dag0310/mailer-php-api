# Mailer - PHP API

Send emails using an HTTP PHP API using PHPMailer.

## Installation

- Just copy the `www` folder to your webserver.
- Make sure to rename `config.template.ini` to `config.ini` and set the fields.
- Make sure `config.ini` is inaccessible through the web, .htaccess does this already for Apache servers.

## Usage examples

### PHP
```php
$request = new HttpRequest();
$request->setUrl('https://example.com/mailer/');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders([
  'Content-Type' => 'application/x-www-form-urlencoded',
  'Authorization' => 'Bearer API_TOKEN_GOES_HERE'
]);

$request->setContentType('application/x-www-form-urlencoded');
$request->setPostFields([
  'to_email' => 'john.doe@example.com',
  'subject' => 'Test email',
  'message_html' => 'HTML message body',
]);

try {
  $response = $request->send();

  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
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
