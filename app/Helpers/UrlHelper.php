<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class UrlHelper
{
    public static function appendTo(string $url, string $append, $override = false): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url . $append;
            // throw new LogicException('wrong url format');
        }

        $parts = parse_url($url);

        parse_str(Arr::get($parts, "query"), $query);
        parse_str($append, $append_query);


        $parts['query'] = urldecode(
            http_build_query($override
                ? array_merge($query, $append_query)
                : array_merge($append_query, $query)
            )
        );

        return self::reverse_parse_url($parts);

    }

    public static function reverse_parse_url(array $parts)
    {
        $url = '';
        if (!empty($parts['scheme'])) {
            $url .= $parts['scheme'] . ':';
        }
        if (!empty($parts['user']) || !empty($parts['host'])) {
            $url .= '//';
        }
        if (!empty($parts['user'])) {
            $url .= $parts['user'];
        }
        if (!empty($parts['pass'])) {
            $url .= ':' . $parts['pass'];
        }
        if (!empty($parts['user'])) {
            $url .= '@';
        }
        if (!empty($parts['host'])) {
            $url .= $parts['host'];
        }
        if (!empty($parts['port'])) {
            $url .= ':' . $parts['port'];
        }
        if (!empty($parts['path'])) {
            $url .= $parts['path'];
        }
        if (!empty($parts['query'])) {
            if (is_array($parts['query'])) {
                $url .= '?' . http_build_query($parts['query']);
            } else {
                $url .= '?' . $parts['query'];
            }
        }
        if (!empty($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }
}
