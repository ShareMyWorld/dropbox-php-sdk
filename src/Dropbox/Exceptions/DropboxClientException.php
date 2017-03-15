<?php
namespace Kunnu\Dropbox\Exceptions;

use Exception;

/**
 * Base exception class for all exceptions thrown by this SDK.
 *
 * Instances of base class thrown when the SDK detects that a method was called with illegal parameter values,
 * or another error happened before the request was sent.
 *
 * For specific exceptions related with error responses from the Dropbox API, see subclasses.
 */
class DropboxClientException extends Exception
{
}
