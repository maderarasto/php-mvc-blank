<?php

namespace Lib\Application;
use Exception;
use Lib\Application\Traits\CanRenderView;
use parallel\Events\Event\Type;

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

    public function __construct(int $code = self::STATUS_OK)
    {
        $this->code = $code;
        $this->headers = [];
        $this->data = [];
        $this->view = '';
    }

    public function status(int|null $code = null)
    {
        if (!$code) {
            return $this->code;
        }

        $this->code = $code;
        return $this;
    }

    public function header(string $key, mixed $value = null)
    {
        if ($value == null) {
            return in_array($key, $this->headers) ? $this->headers[$key] : null;
        }

        $this->headers[$key] = $value;
        return $this;
    }

    public function view(string $view, array $data = [])
    {
        $this->type = self::TYPE_VIEW;
        $this->view = $view;
        $this->data = $data;

        return $this;
    }

    public function json(array $data = [])
    {
        $this->type = self::TYPE_JSON;
        $this->data = $data;
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';

        return $this;
    }

    public function handle()
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        if ($this->type == self::TYPE_JSON) {
            echo json_encode($this->data);
        } else if ($this->type == self::TYPE_VIEW) {
            $this->renderView($this->view, $this->data);
        }
    }
}