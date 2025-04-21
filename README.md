
## Example Usage

Below is an example of how this SDK can be used in a Laravel-style project to support both OAuth and Server-to-Server Zoom integrations.

### Initialization Example

```php
use Msaaq\Zoom\AccessToken;
use Msaaq\Zoom\Zoom;
use Msaaq\Zoom\Clients\OAuthClient;
use Msaaq\Zoom\Clients\ServerToServerOAuthClient;

// OAuth setup
$client = new OAuthClient(
    clientId: 'client_id',
    clientSecret: 'client_secret',
    redirectUri: 'redirect_url'
);

// Server-to-Server setup
$client = new ServerToServerOAuthClient(
    clientId: 'client_id',
    clientSecret: 'client_secret',
    accountId: 'account_id',
);

$accessToken = new AccessToken(
    client: $client,
    accessToken: 'your_zoom_access_token',
    refreshToken: 'your_refresh_token',
    onTokenChange: function (array $newTokens) {
        // store new token values
    }
);

$zoom = new Zoom($accessToken);
$userInfo = $zoom->user()->get();

// Get All Meetings
$zoom->user()->meetings()->all();
// Create a Meeting
$meeting = new \Msaaq\Zoom\Models\Meeting();
$meeting->topic = 'Meeting Topic';
$zoom->user()->meetings()->create($meeting);

// Get All Webinars
$zoom->user()->webinars()->all();
// Create a Webinar
$zoom->user()->webinars()->create(// Webinar object);


```

### OAuth Flow Example

```php
// Step 1: Redirect user to Zoom OAuth
$client = new OAuthClient(
    clientId: 'client_id',
    clientSecret: 'client_secret',
    redirectUri: 'redirect_url'
);

$url = $client->getAuthorizationUrl();

return redirect()->to($url);

// Step 2: Handle callback
$tokenData = $client->createToken($code);
```

### Refresh Token Example

```php
$newTokens = $client->refreshToken('existing_refresh_token');
```

This example demonstrates how to:
- Support both OAuth and Server-to-Server authentication
- Automatically refresh tokens and persist new ones
- Interact with the Zoom API using Laravel-inspired syntax
