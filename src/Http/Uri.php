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

    private $uriSchemeMap = ['http','https'];

    public function __construct(string $url)
    {
        $this->scheme   = (string) parse_url($url, PHP_URL_SCHEME);
        $this->host     = (string) parse_url($url, PHP_URL_HOST);

        $port           = (int) parse_url($url, PHP_URL_PORT);
        $this->port     = $port === 0 ? null : $port;

        $this->path     = (string) parse_url($url, PHP_URL_PATH);
        $this->query    = (string) parse_url($url, PHP_URL_QUERY);
        $this->fragment = (string) parse_url($url, PHP_URL_FRAGMENT);

        $this->setUserInfo($url);
        $this->setAuthority($url);
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    private function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @param string $host
     * @return Uri|UriInterface
     */
    public function withHost($host)
    {
        $uri = clone $this;
        $uri->setHost($host);

        return $uri;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    private function setScheme($scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * @param string $scheme
     * @return Uri|UriInterface
     */
    public function withScheme($scheme)
    {
        $uri = clone $this;

        if(!in_array($scheme,$this->uriSchemeMap)){
            throw new \InvalidArgumentException('Unsupported schemes!');
        }

        $uri->setScheme($scheme);

        return $uri;
    }

    /**
     * @return string
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @param mixed $userInfo
     */
    private function setUserInfo($userInfo): void
    {
        $userName = parse_url($userInfo, PHP_URL_USER);
        $password = parse_url($userInfo, PHP_URL_PASS);
        $this->userInfo = $userName . ($password ? ':' . $password : '');
    }

    /**
     * @param string $user
     * @param null|string $password
     * @return Uri|UriInterface
     */
    public function withUserInfo($user, $password = null)
    {
        $uri            = clone $this;
        $uri->userInfo = $user ? $user . ($password == null ? '' : ':' . $password) : '';

        return $uri;
    }

    /**
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * @param mixed $authority
     */
    private function setAuthority($authority): void
    {
        $userInfo   = $this->getUserInfo();
        $host       = $this->getHost();
        $port       = $this->getPort();

        $this->authority = ($userInfo ? $userInfo . '@' : '') . $host . ($port === null ? '' : ':' . $port);
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param $port
     */
    private function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @param int|null $port
     * @return Uri|UriInterface
     */
    public function withPort($port)
    {
        $uri = clone $this;

        if(($port <= 0) or ($port >= 65536)){
            throw new \InvalidArgumentException('Invalid Port!');
        }

        $uri->setPort($port);

        return $uri;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    private function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     * @return Uri|UriInterface
     */
    public function withPath($path)
    {
        $uri = clone $this;
        $uri->setPath($path);

        return $uri;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    private function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * @param string $query
     * @return Uri|UriInterface
     */
    public function withQuery($query)
    {
        $uri = clone $this;
        $uri->setQuery($query);

        return $uri;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param mixed $fragment
     */
    private function setFragment($fragment): void
    {
        $this->fragment = $fragment;
    }

    /**
     * @param string $fragment
     * @return Uri|UriInterface
     */
    public function withFragment($fragment)
    {
        $uri = clone $this;
        $uri->setFragment($fragment);

        return $uri;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $scheme     = $this->getScheme();
        $authority  = $this->getAuthority();
        $path       = $this->getPath();
        $query      = $this->getQuery();
        $fragment   = $this->getFragment();

        $uri  = $scheme ? $scheme . ':' : '';
        $uri .= $authority ? '//' . $authority : '';
        $uri .= $path ? $path : '';
        $uri .= $query ? '?' . $query : '';
        $uri .= $fragment ? '#' . $fragment : '';

        return $uri;
    }
}
