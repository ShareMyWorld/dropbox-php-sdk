<?php
namespace Kunnu\Dropbox\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Kunnu\Dropbox\Exceptions\DropboxApiEndpointClientException;
use Kunnu\Dropbox\Exceptions\DropboxApiInvalidInputClientException;
use Kunnu\Dropbox\Exceptions\DropboxApiInvalidTokenClientException;
use Kunnu\Dropbox\Exceptions\DropboxApiServerException;
use Kunnu\Dropbox\Exceptions\DropboxApiTooManyRequestsClientException;
use Kunnu\Dropbox\Exceptions\DropboxApiUnknownClientException;
use Kunnu\Dropbox\Exceptions\DropboxRequestException;
use Kunnu\Dropbox\Models\EndpointError;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Kunnu\Dropbox\Http\DropboxRawResponse;

/**
 * DropboxGuzzleHttpClient
 */
class DropboxGuzzleHttpClient implements DropboxHttpClientInterface
{
    /**
     * GuzzleHttp client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new DropboxGuzzleHttpClient instance
     *
     * @param Client $client GuzzleHttp Client
     */
    public function __construct(Client $client = null)
    {
        //Set the client
        $this->client = $client ?: new Client();
    }

    /**
     * Send request to the server and fetch the raw response
     *
     * @param  string $url URL/Endpoint to send the request to
     * @param  string $method Request Method
     * @param  string|resource|StreamInterface $body Request Body
     * @param  array $headers Request Headers
     * @param  array $options Additional Options
     * @return DropboxRawResponse Raw response from the server
     *
     * @throws DropboxApiEndpointClientException
     * @throws DropboxApiInvalidInputClientException
     * @throws DropboxApiInvalidTokenClientException
     * @throws DropboxApiServerException
     * @throws DropboxApiTooManyRequestsClientException
     * @throws DropboxApiUnknownClientException
     * @throws DropboxRequestException
     */
    public function send($url, $method, $body, $headers = [], $options = [])
    {
        //Create a new Request Object
        $request = new Request($method, $url, $headers, $body);

        try {
            //Send the Request
            $rawResponse = $this->client->send($request, $options);
        } catch (RequestException $e) {
            $rawResponse = $e->getResponse();

            if (!$rawResponse instanceof ResponseInterface) {
                throw new DropboxRequestException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $httpStatusCode = $rawResponse->getStatusCode();

        if ($httpStatusCode >= 400) {
            $body = $this->getResponseBody($rawResponse);

            if ($httpStatusCode >= 500) {
                throw new DropboxApiServerException($body, $httpStatusCode);
            }

            //Something went wrong
            switch ($httpStatusCode) {
                case 400:
                    throw new DropboxApiInvalidInputClientException($body, $httpStatusCode);

                case 401:
                    throw new DropboxApiInvalidTokenClientException($body, $httpStatusCode);

                case 409:
                    $decodedBody = json_decode($body, TRUE);
                    if (!is_array($decodedBody) || !is_array($decodedBody['error'])) {
                        throw new DropboxApiUnknownClientException($body, $httpStatusCode);
                    }
                    $endpointError = new EndpointError($decodedBody);
                    throw new DropboxApiEndpointClientException($endpointError, $body, $httpStatusCode);

                case 429:
                    throw new DropboxApiTooManyRequestsClientException($body, $httpStatusCode);

                default:
                    throw new DropboxApiUnknownClientException($body, $httpStatusCode);
            }
        }


        if (array_key_exists('sink', $options)) {
            //Response Body is saved to a file
            $body = '';
        } else {
            //Get the Response Body
            $body = $this->getResponseBody($rawResponse);
        }

        $rawHeaders = $rawResponse->getHeaders();

        //Create and return a DropboxRawResponse object
        return new DropboxRawResponse($rawHeaders, $body, $httpStatusCode);
    }

    /**
     * Get the Response Body
     *
     * @param string|\Psr\Http\Message\ResponseInterface $response Response object
     *
     * @return string
     */
    protected function getResponseBody($response)
    {
        //Response must be string
        $body = $response;

        if ($response instanceof ResponseInterface) {
            //Fetch the body
            $body = $response->getBody();
        }

        if ($body instanceof StreamInterface) {
            $body = $body->getContents();
        }

        return $body;
    }
}
