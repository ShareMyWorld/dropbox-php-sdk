<?php

namespace Kunnu\Dropbox\Exceptions;


/**
 * Your app is making too many requests for the given user or team and is being rate limited.
 * Your app should wait for the number of seconds specified in the "Retry-After" response header before trying again.
 * The Content-Type of the response can be JSON or plaintext. If it is JSON, it will have a reason field with one of the following values:
 * too_many_requests Void Your app has been making too many requests in the past few minutes.
 * too_many_write_operations Void They are too many write operations happening in the user's Dropbox.
 */
class DropboxApiTooManyRequestsClientException extends DropboxApiClientException {

}