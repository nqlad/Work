<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 11.07.18
 * Time: 15:14
 */

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
        $this->scheme = parse_url($url, PHP_URL_SCHEME);
        $this->setAuthority($url);
        $this->setUserInfo($url);
        $this->host = parse_url($url, PHP_URL_HOST);
        $this->port = (int)parse_url($url, PHP_URL_PORT) == 0 ? null : (int)parse_url($url, PHP_URL_PORT);
        $this->path = parse_url($url, PHP_URL_PATH);
        $this->query = parse_url($url, PHP_URL_QUERY);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);
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
        $uri = clone $this;
        $uri->setUserInfo($user);
        $uri->setPass($password);

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
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass): void
    {
        $this->pass = $pass;
    }


    public function withPort($port)
    {
        $uri = clone $this;
        $uri->setPort($port);

        return $uri;
    }

    public function withPath($path)
    {
        $uri = clone $this;
        $uri->setPath($path);

        return $uri;
    }

    public function withQuery($query)
    {
        $uri = clone $this;
        $uri->setQuery($query);

        return $uri;
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
        // TODO: Implement __toString() method.
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * @param mixed $fragment
     */
    public function setFragment($fragment): void
    {
        $this->fragment = $fragment;
    }

    /**
     * @param mixed $authority
     */
    public function setAuthority($authority): void
    {
        $userInfo = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();
        $this->authority = $authority;
    }
}