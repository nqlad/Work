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

    public function __construct(string $url)
    {
        $this->scheme   = parse_url($url, PHP_URL_SCHEME);
        $this->host     = parse_url($url, PHP_URL_HOST);

        $port           = (int) parse_url($url, PHP_URL_PORT);
        $this->port     = $port === 0 ? null : $port;

        $this->path     = parse_url($url, PHP_URL_PATH);
        $this->query    = parse_url($url, PHP_URL_QUERY);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        $this->setUserInfo($url);
        $this->setAuthority($url);
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    public function withHost($host)
    {
        $uri = clone $this;
        $uri->setHost($host);

        return $uri;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setScheme($scheme): void
    {
        $this->scheme = $scheme;
    }

    public function withScheme($scheme)
    {
        $uri = clone $this;
        $uri->setScheme($scheme);

        return $uri;
    }

    /**
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @param mixed $userInfo
     */
    public function setUserInfo($userInfo): void
    {
        $userName = parse_url($userInfo, PHP_URL_USER);
        $password = parse_url($userInfo, PHP_URL_PASS);
        $this->userInfo = $userName . ($password ? ':' . $password : '');
    }

    public function withUserInfo($user, $password = null)
    {
        $uri            = clone $this;
        $uri->userInfo = $user ? $user . ($password == null ? '' : ':' . $password) : '';

        return $uri;
    }

    /**
     * @return mixed
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
    public function setAuthority($authority): void
    {
        $userInfo   = $this->getUserInfo();
        $host       = $this->getHost();
        $port       = $this->getPort();

        $this->authority = ($userInfo ? $userInfo . '@' : '') . $host . ($port === null ? '' : ':' . $port);
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    public function withPort($port)
    {
        $uri = clone $this;
        $uri->setPort($port);

        return $uri;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function withPath($path)
    {
        $uri = clone $this;
        $uri->setPath($path);

        return $uri;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
    }

    public function withQuery($query)
    {
        $uri = clone $this;
        $uri->setQuery($query);

        return $uri;
    }

    /**
     * @return mixed
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param mixed $fragment
     */
    public function setFragment($fragment): void
    {
        $this->fragment = $fragment;
    }

    public function withFragment($fragment)
    {
        $uri = clone $this;
        $uri->setFragment($fragment);

        return $uri;
    }

    /**
     * Return the string representation as a URI reference.
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

