<?php

namespace Kunnu\Dropbox\Exceptions;


/**
 * Thrown when sending a request to Dropbox API with a bad or expired token.
 * This can happen if the access token is expired or if the access token has been revoked by Dropbox or the user. To fix this, you should re-authenticate the user.
 */
class DropboxApiInvalidTokenClientException extends DropboxApiClientException {

}