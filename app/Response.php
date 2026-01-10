<?php
declare(strict_types=1);

namespace App;

use GuzzleHttp\Psr7\Stream;
use http\Exception\RuntimeException;
use Override;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private StreamInterface $body;
    private int $statusCode = 200;
    private string $reasonPhrase = 'OK';

    public function __construct(
        string $body = '',
        array $headers = [],
        int $status = 200,
        string $reason = ''
    ) {
        $this->body = $this->createStream($body);
        $this->headers = $this->normalizeHeaders($headers);
        $this->statusCode = $status;
        $this->reasonPhrase = $reason ?: $this->getDefaultReason($status);
    }

    #[Override]
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    #[Override]
    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    #[Override]
    public function getHeaders(): array
    {
        return $this->headers;
    }

    #[Override]
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    #[Override]
    public function getHeader(string $name): array
    {
        $key = strtolower($name);
        return $this->hasHeader($name) ? $this->headers[$key] : [];
    }

    #[Override]
    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    #[Override]
    public function withHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)] = $this->normalizeHeaderValue($value);
        return $clone;
    }

    #[Override]
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)][] = $this->normalizeHeaderValue($value);
        return $clone;
    }

    #[Override]
    public function withoutHeader(string $name): MessageInterface
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    #[Override]
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    #[Override]
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

    #[Override]
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    #[Override]
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase ?: $this->getDefaultReason($code);
        return $clone;
    }

    #[Override]
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
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
