<?php

namespace Kunnu\Dropbox\Exceptions;

/**
 * Thrown if a 4XX error is returned by Dropbox API that was not recognized by the SDK
 */
class DropboxApiUnknownClientException extends DropboxApiClientException {

}