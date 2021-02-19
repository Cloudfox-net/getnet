<?php


namespace CloudFox\GetNet\Http\Requests;


class OptionsRequest
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';

    public ?string $method, $url, $bearer, $basic = null;
    public array $content = [];
    public array $headers = [];

    public function __construct()
    {
        $this->method = null;
        $this->url = null;
        $this->bearer = null;
        $this->basic = null;
        $this->content = [];
        $this->headers = [];

    }
}
