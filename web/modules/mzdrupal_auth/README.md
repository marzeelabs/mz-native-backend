# json_web_token
Adapted from https://github.com/shaksi/json_web_token

Drupal json web token based on https://dropsolid.com/en/blog/drupal-8-and-react-native

cCreates an api endpoint on Drupal 8, which requires username/password to authenticate against Drupal.
A nice starting point to work with react native etc.

### Endpoint
```
/api/v1/tokn
```

Granularity - resource
Method - POST
Authentication - json_auth (implemented in this module as well)

example Curl Request

```curl
curl -X POST \
  'http://papa.lab/api/token?_format=json' \
  -H 'authorization: json_auth' \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{"name": "admin", "pass": "admin"}'
```
