<?php

namespace Kunnu\Dropbox\Exceptions;

use Exception;
use Kunnu\Dropbox\Models\EndpointError;

/**
 * Thrown when the Dropbox API returns an endpoint specific exception.
 */
class DropboxApiEndpointClientException extends DropboxApiClientException {

    /**
     * @var EndpointError
     */
    protected $endpointError;

    /**
     * DropboxApiEndpointClientException constructor.
     *
     * @param EndpointError $endpointError
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($endpointError, $message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->endpointError = $endpointError;
    }

    /**
     * Returns the end-point error that caused this exception.
     *
     * @return EndpointError
     */
    public function getEndpointError() {
        return $this->endpointError;
    }

}