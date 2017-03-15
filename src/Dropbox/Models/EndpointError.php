<?php

namespace Kunnu\Dropbox\Models;


class EndpointError extends BaseModel {

    /**
     * @var array
     */
    protected $error;

    /**
     * @var string
     */
    protected $error_summary;

    /**
     * @var string|null
     */
    protected $user_message;

    /**
     * Create a new EndpointError instance
     *
     * @param array $data
     */
    public function __construct(array $data) {
        parent::__construct($data);
        $this->error = $this->getDataProperty('error');
        $this->error_summary = $this->getDataProperty('error_summary');
        $this->user_message = $this->getDataProperty('user_message');
    }

    /**
     * A value that conforms to the error data type schema defined in the definition of each route.
     *
     * @return array
     */
    public function getErrorData() {
        return $this->error;
    }

    /**
     * Get the error type string (".tag" value)
     *
     * @return string|null
     */
    public function getErrorType() {
        if (isset($this->error['.tag'])) {
            return $this->error['.tag'];
        }
        return null;
    }

    /**
     * A string that summarizes the value of the "error" key.
     * It is a concatenation of the hierarchy of union tags that make up the error.
     * While this provides a human-readable error string, "error_summary" should not be used for programmatic error handling.
     * To disincentive this, we append a random number of "." characters at the end of the string.
     *
     * @return string
     */
    public function getErrorSummary() {
        return $this->error_summary;
    }

    /**
     * An optional field. If present, it includes a message that can be shown directly to the end user of your app.
     * You should show this message if your app is unprepared to programmatically handle the error returned by an endpoint.
     *
     * @return string|null
     */
    public function getUserMessage() {
        return $this->user_message;
    }
}