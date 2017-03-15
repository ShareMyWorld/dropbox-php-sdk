<?php

namespace Kunnu\Dropbox\Exceptions;

/**
 * Thrown when we an exception occurred in-flight, i.e. the HTTP request failed before the full response was retrieved.
 */
class DropboxRequestException extends DropboxClientException {

}