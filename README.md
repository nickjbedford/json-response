# JsonResponse

The JsonResponse library for PHP defines a simple and uniform structure
for JSON API responses, from success status to error information and
data payloads.

All keys are guaranteed to exist, even if they are null. This ensures
clients of an API do not have to waste time and code checking if
standard properties exist before checking their values.

## Success Responses

To create a **success** response, use either the `\YetAnother\JsonResponse::success()` method or the `json_success()` function.

### Example

```php
$response = json_success();

$json = $response->toJSON();
$json = strval($response); // This calls $response->toJSON()

// Create a response with a payload
$personResponse = json_success([
    'id' => 123,
    'name' => 'John Citizen',
    'dob' => '1985-01-01'
]);
```

The basic response structure for a successful request without any
data payload is as follows:

```json
{
    "success": true,
    "error": null,
    "data": null
}
```

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | `true` for a successful response, otherwise `false` for an error response. |
| `error` | object or `null` | `null` for successful responses, otherwise a `JsonResponseError` object. |
| `data` | mixed or `null` | An optional data payload containing any type of data including `null`. This will depend on the API request.

## Error Responses

To create a **failure** response, use either the `\YetAnother\JsonResponse::failure()` method or the `json_success()` function.

### Example

```php
$response = json_failure("Person not found.", "E_NOTFOUND");

$json = $response->toJSON();
```

The basic response structure for a failed request is as follows:

```json
{
    "success": true,
    "error": {
        "message": "Some error message...",
        "code": null,
        "data": null
    },
    "data": null
}
```

| Field | Type | Description |
|-------------|------|-------------|
| `error.message` | string | An error message for the client to display to the user. |
| `error.code` | string or `null` | An optional error code for the client to use in handling the error. This will depend on the API request. |
| `error.data` | mixed or `null` | Optional information about the error. This may contain debugging information about exceptions thrown by the server if provided. |

### Exception Error Responses

To create a **failure** response based on an exception, use either the `\YetAnother\JsonResponse::exception()` method or the `json_exception()` function.

### Example

```php
$exception = new Exception("Database connection was lost.", "E_DBCONN");

$response = json_exception($exception, true); // true to include debugging information

$json = $response->toJSON();
```

The response will contain the following data including the exception debug data:

```json
{
    "success": false,
    "error": {
        "message": "Database connection was lost.",
        "code": "E_DBCONN",
        "data": {
            "class": "Exception",
            "file": "/home/user/documents/my-project/example.php",
            "line": 34,
            "trace": "<stack trace...>"
        }
    }
}
```

## Record Sets

To create a standardised response for record set responses with paging information,
use the `\YetAnother\JsonResponse::recordSet()` method or `json_records()` function.

### Example

```php
$records = [ 10, 20, 30, 40, 50 ];

$response = json_records($records, 2, 5, 30, 'Integer');

$json = $response->toJSON();
```

The response will contain the following data including the exception debug data:

```json
{
    "success": true,
    "error": null,
    "data": {
        "records": [ 10, 20, 30, 40, 50 ],
        "page": 2,
        "pageLength": 5,
        "total": 30,
        "nextPage": 3,
        "previousPage": 1,
        "recordType": "Integer"
    }
}
```

| Field | Type | Description |
|-------------|------|-------------|
| `data.records` | array | An array containing the page's records. |
| `data.page` | int | The zero-based page index for the current record set. |
| `data.pageLength` | int | The length of each page in the query. |
| `data.total` | int | The total number of records in the query. |
| `data.nextPage` | int or `null` | The next page's index if there are more records, otherwise `null`. |
| `data.previousPage` | int or `null` | The previous page's index if the current page index is non-zero, otherwise `null`. |
| `data.recordType` | string or `null` | An optionally provided name of the type of records in the record set. This is defined by the API using the library. |