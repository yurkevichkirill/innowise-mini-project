<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Psr7\Stream;
use http\Exception\RuntimeException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    private UriInterface $uri;
    private string $method;
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private StreamInterface $body;
    private string $requestTarget;

    public function __construct(
        UriInterface $uri,
        string $method,
        string $body = '',
        array $headers = []
    )
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->requestTarget = $this->uri->getPath();
        if($this->uri->getQuery()) {
            $this->requestTarget .= "?" . $this->uri->getQuery();
        }
        $this->body = $this->createStream($body);
        $this->headers = $this->normalizeHeaders($headers);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader(string $name): array
    {
        $key = strtolower($name);
        return $this->hasHeader($name) ? $this->headers[$key] : [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)] = $this->normalizeHeaderValue($value);
        return $clone;
    }

    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            $normalized[strtolower($name)] = $this->normalizeHeaderValue($value);
        }
        return $normalized;
    }

    private function normalizeHeaderValue($value): array
    {
        $value = is_array($value) ? $value : [$value];
        return array_filter(array_map('strval', $value));
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)][] = $this->normalizeHeaderValue($value);
        return $clone;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    private function createStream(string $str): StreamInterface
    {
        $stream = fopen("php://temp", 'r+');
        if(!$stream) {
            throw new RuntimeException("Cannot create stream");
        }

        fwrite($stream, $str);
        rewind($stream);
        return new Stream($stream);
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $clone = clone $this;
        if(!$preserveHost) {
            $hostHeader = $uri->getHost();
            if ($port = $uri->getPort()) {
                $hostHeader .= ':' . $port;
            }
            $clone->headers['host'] = [$hostHeader];
        }
        return $clone;
    }
}