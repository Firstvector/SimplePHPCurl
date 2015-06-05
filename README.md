# SimplePHPCurl

Simple library for performing **cURL** requests.

## Usage

An example of request with authorization via OAuth or Bearer token.

```php
$token = new SimplePHPCurl\OAuthToken(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_ACCESS_KEY, OAUTH_ACCESS_SECRET);
// $token = new SimplePHPCurl\BearerToken('https://api.twitter.com/oauth2/token', CONSUMER_KEY, CONSUMER_SECRET);

$curl = new SimplePHPCurl\Request('https://api.twitter.com/1.1/statuses/user_timeline.json');
$curl->authorization($token);
$curl->get([
    'screen_name' => 'firstvector',
    'count' => 2,
]);
var_dump(
    $curl->execute()
);
```

An example of simple GET request.

```php
$curl = new SimplePHPCurl\Request('http://firstvector.org');
$curl->get();
var_dump(
    $curl->execute()
);
```
