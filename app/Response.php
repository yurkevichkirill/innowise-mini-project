<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Psr7\Stream;
use http\Exception\RuntimeException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private ?StreamInterface $body;
    private int $statusCode = 200;
    private string $reasonPhrase = 'OK';

    public function __construct(
        string $body = '',
        array $headers = [],
        int $status = 200,
        string $reason = ''
    ) {
        $this->body = $this->createStream($body) ?? $this->createEmptyStream();
        $this->headers = $this->normalizeHeaders($headers);
        $this->statusCode = $status;
        $this->reasonPhrase = $reason ?: $this->getDefaultReason($status);
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

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase ?: $this->getDefaultReason($code);
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function createEmptyStream(): StreamInterface
    {
        $stream = fopen('php://temp', 'r+');
        if (!$stream) throw new \RuntimeException('Cannot create stream');
        return new Stream($stream);
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

    private function getDefaultReason(int $code): string
    {
        $reasons = [
            200 => 'OK', 201 => 'Created', 204 => 'No Content',
            400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found',
            500 => 'Internal Server Error'
        ];
        return $reasons[$code] ?? '';
    }
}
