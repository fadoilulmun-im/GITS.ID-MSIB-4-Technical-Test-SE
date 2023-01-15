<?php

function setResponse(
  $data = null, 
  $message = null,
  $code = 200
){
  switch ($code) {
		case 200:
			$defaultStatus = 'OK';
			$defaultMessage = 'Success';
		  break;
		case 201:
			$defaultStatus = 'Created';
			$defaultMessage = 'Successfully created data';
		  break;
    case 304:
			$defaultStatus = 'Not Modified';
			$defaultMessage = 'There was no new data to return.';
		  break;
    case 400:
			$defaultStatus = 'Bad Request';
			$defaultMessage = 'The request was invalid or cannot be otherwise served.';
		  break;
    case 401:
			$defaultStatus = 'Unauthorized';
			$defaultMessage = 'Missing or incorrect authentication credentials.';
		  break;
    case 403:
			$defaultStatus = 'Forbidden';
			$defaultMessage = 'The request but it has been refused or access is not allowed.';
		  break;
    case 404:
			$defaultStatus = 'Not Found';
			$defaultMessage = 'Data not found';
		  break;
    case 406:
			$defaultStatus = 'Not Acceptable';
			$defaultMessage = 'Invalid request format.';
		  break;
    case 410:
			$defaultStatus = 'Gone';
			$defaultMessage = 'This resource is gone. API endpoint has been turned off.';
		  break;
    case 420:
			$defaultStatus = 'Enhance Your Calm';
			$defaultMessage = 'Rate limited.';
		  break;
    case 422:
			$defaultStatus = 'Unprocessable Entity';
			$defaultMessage = 'The data is unable to be processed.';
		  break;
    case 429:
			$defaultStatus = 'Too Many Requests';
			$defaultMessage = 'The apps rate limit having been exhausted for the resource.';
		  break;
    case 500:
			$defaultStatus = 'Internal Server Error';
			$defaultMessage = 'Something is broken.';
		  break;
    case 502:
			$defaultStatus = 'Bad Gateway';
			$defaultMessage = 'The apps is down, or being upgraded.';
		  break;
    case 503:
			$defaultStatus = 'Service Unavailable';
			$defaultMessage = 'The server was overloaded with requests. Try again later.';
		  break;
    case 504:
			$defaultStatus = 'Gateway timeout';
			$defaultMessage = 'The request couldn`t be serviced due to some failure within the internal stack.';
		  break;
		default:
			$defaultStatus = 'Undefined';
			$defaultMessage = 'Undefined';
		  break;
	}

  $result = [
    'status' => [
      'code' => $code,
      'name' => $defaultStatus,
      'message' => $message ?? $defaultMessage,
    ],
    'data' => $data
  ];

  return response()->json($result, $code);
}