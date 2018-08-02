<?php

namespace App\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private $scheme;

    private $authority;

    private $userInfo;

    private $host;

    private $port;

    private $path;

    private $query;

    private $fragment;

    private $uriSchemeMap = ['http', 'https'];

    public function __construct(?string $url)
    {
        $this->scheme   = (string) parse_url($url, PHP_URL_SCHEME);
        $this->host     = (string) parse_url($url, PHP_URL_HOST);

        $port           = (int) parse_url($url, PHP_URL_PORT);
        $this->port     = 0 === $port ? null : $port;

        $this->path     = (string) parse_url($url, PHP_URL_PATH);
        $this->query    = (string) parse_url($url, PHP_URL_QUERY);
        $this->fragment = (string) parse_url($url, PHP_URL_FRAGMENT);

        $this->setUserInfo($url);
        $this->setAuthority();
    }

    public function getHost(): string
    {
        return $this->host;
    }

    private function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @param string $host
     */
    public function withHost($host): UriInterface
    {
        $uri = clone $this;
        $uri->setHost($host);

        return $uri;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    private function setScheme($scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * @param string $scheme
     */
    public function withScheme($scheme): UriInterface
    {
        $uri = clone $this;

        if (!in_array($scheme, $this->uriSchemeMap, true)) {
            throw new \InvalidArgumentException('Unsupported schemes!');
        }

        $uri->setScheme($scheme);

        return $uri;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    private function setUserInfo($userInfo): void
    {
        $userName       = parse_url($userInfo, PHP_URL_USER);
        $password       = parse_url($userInfo, PHP_URL_PASS);

        $this->userInfo = $userName.($password ? ':'.$password : '');
    }

    /**
     * @param string $user
     * @param null   $password
     */
    public function withUserInfo($user, $password = null): UriInterface
    {
        $uri            = clone $this;
        $uri->userInfo  = $user ? $user.(null === $password ? '' : ':'.$password) : '';

        return $uri;
    }

    public function getAuthority(): string
    {
        $authority = $this->host;

        if ('' !== $this->userInfo) {
            $authority = $this->userInfo.'@'.$authority;
        }

        if (null !== $this->port) {
            $authority .= ':'.$this->port;
        }

        return $authority;
    }

    private function setAuthority(): void
    {
        $userInfo   = $this->getUserInfo();
        $host       = $this->getHost();
        $port       = $this->getPort();

        $this->authority = ($userInfo ? $userInfo.'@' : '').$host.(null === $port ? '' : ':'.$port);
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    private function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @param int|null $port
     */
    public function withPort($port): UriInterface
    {
        $uri = clone $this;

        if (($port <= 0) or ($port >= 65536)) {
            throw new \InvalidArgumentException('Invalid Port!');
        }

        $uri->setPort($port);

        return $uri;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     */
    public function withPath($path): UriInterface
    {
        $uri = clone $this;
        $uri->setPath($path);

        return $uri;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    private function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * @param string $query
     */
    public function withQuery($query): UriInterface
    {
        $uri = clone $this;
        $uri->setQuery($query);

        return $uri;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    private function setFragment($fragment): void
    {
        $this->fragment = $fragment;
    }

    /**
     * @param string $fragment
     */
    public function withFragment($fragment): UriInterface
    {
        $uri = clone $this;
        $uri->setFragment($fragment);

        return $uri;
    }

    public function __toString(): string
    {
        $scheme     = $this->getScheme();
        $authority  = $this->getAuthority();
        $path       = $this->getPath();
        $query      = $this->getQuery();
        $fragment   = $this->getFragment();

        $uri  = $scheme ? $scheme.':' : '';
        $uri .= $authority ? '//'.$authority : '';
        $uri .= $path ?: '';
        $uri .= $query ? '?'.$query : '';
        $uri .= $fragment ? '#'.$fragment : '';

        return $uri;
    }
}
