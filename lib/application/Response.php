<?php

namespace Lib\Application;
use Exception;
use Lib\Application\Traits\CanRenderView;
use parallel\Events\Event\Type;

/**
 * Represents a response object that provides repond to user with view or json.
 */
class Response
{
    // Statuses
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_NOT_PROCESSABLE_ENTITY = 422;
    const STATUS_SERVER_ERROR = 500;

    // Types
    const TYPE_VIEW = 1;
    const TYPE_JSON = 2;

    private int $code;
    private array $headers;
    private array $data;
    private string $view;
    private int $type;

    use CanRenderView;

    /**
     * Initialized a response with statuc code. If status code is not present it will set status OK automatically.
     * 
     * @param int $code http status code
     */
    public function __construct(int $code = self::STATUS_OK)
    {
        $this->code = $code;
        $this->headers = [];
        $this->data = [];
        $this->view = '';
    }

    /**
     * Sets HTTP status code for response. If status code is not present then it will return status code.
     * 
     * @param int|null $code 
     * @return int|Response
     */
    public function status(int|null $code = null)
    {
        if (!$code) {
            return $this->code;
        }

        $this->code = $code;
        return $this;
    }

    /**
     * Set a response header with given key and value. If value is not present then it will return header of response.
     * 
     * @param string $key 
     * @param mixed $value 
     * @return mixed
     */
    public function header(string $key, mixed $value = null)
    {
        if ($value == null) {
            return in_array($key, $this->headers) ? $this->headers[$key] : null;
        }

        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Assign view to response object that will be later handled.
     * 
     * @param string $view 
     * @param array $data 
     * @return Response
     */
    public function view(string $view, array $data = [])
    {
        $this->type = self::TYPE_VIEW;
        $this->view = $view;
        $this->data = $data;

        return $this;
    }

    /**
     * Assign JSON to response object that will be later handled.
     * 
     * @param array $data 
     * @return Response
     */
    public function json(array $data = [])
    {
        $this->type = self::TYPE_JSON;
        $this->data = $data;
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';

        return $this;
    }

    /**
     * Handles a response object after processing it in controllers.
     */
    public function handle()
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        http_response_code($this->code);

        if ($this->type == self::TYPE_JSON) {
            echo json_encode($this->data);
        } else if ($this->type == self::TYPE_VIEW) {
            $this->renderView($this->view, $this->data);
        }
    }
}