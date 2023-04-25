<?php

namespace WPTraitTests\Unit;

use WPTrait\Http\HTTP;
use WPTrait\Utils\Json;

class HTTPClassTest extends \PHPUnit\Framework\TestCase
{

    public function test_existClass()
    {
        $this->assertTrue(class_exists('\WPTrait\HTTP\HTTP'));
    }

    public function test_extendClass()
    {
        $http = new HTTP();
        $this->assertInstanceOf('\WPTrait\Abstracts\Result', $http);
    }

    public function test_isObjectClass()
    {
        $http = new HTTP();
        $this->assertIsObject($http);
    }

    public function test_existPropertyObjectClass()
    {
        $properties = [
            'url',
            'method',
            'timeout',
            'redirection',
            'version',
            'useragent',
            'headers',
            'cookies',
            'body',
            'ssl',
            'curl',
            'reject_unsafe_urls',

            # Extended
            'response',
            'params'
        ];

        $http = new HTTP();
        foreach ($properties as $property) {
            $this->assertTrue(property_exists($http, $property), 'The ' . $property . ' is not exist in HTTP Class');
        }

        $reflection = new \ReflectionClass($http);
        $property = $reflection->getProperties();
        $this->assertCount(14, $property);
    }

    private function setupGETRequest(): HTTP
    {
        $http = new HTTP();
        $http->get('https://jsonplaceholder.typicode.com/todos/1')
            ->timeout(30)
            ->ssl(false)
            ->redirection(10)
            ->version('1.0')
            ->useragent('Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0')
            ->headers([
                'header_1' => 'header_value_1',
                'header_2' => 'header_value_2',
            ])
            ->cookies([
                'cookie_1' => 'cookie_value_1',
                'cookie_2' => 'cookie_value_2',
            ])
            ->body([
                'name' => 'Mehrshad',
                'family' => 'Darzi'
            ])
            ->unsafe()
            ->curl(function ($handle) {
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            });

        return $http;
    }

    public function test_setupProperty()
    {
        $http = $this->setupGETRequest();
        $reflection = new \ReflectionClass($http);

        $this->assertEquals('https://jsonplaceholder.typicode.com/todos/1', $reflection->getProperty('url')->getValue($http));
        $this->assertEquals('get', $reflection->getProperty('method')->getValue($http));
        $this->assertEquals(30, $reflection->getProperty('timeout')->getValue($http));
        $this->assertEquals(false, $reflection->getProperty('ssl')->getValue($http));
        $this->assertEquals(10, $reflection->getProperty('redirection')->getValue($http));
        $this->assertEquals('1.0', $reflection->getProperty('version')->getValue($http));
        $this->assertEquals(false, $reflection->getProperty('reject_unsafe_urls')->getValue($http));
        $this->assertEquals('Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0', $reflection->getProperty('useragent')->getValue($http));
        $this->assertEquals([
            'header_1' => 'header_value_1',
            'header_2' => 'header_value_2',
        ], $reflection->getProperty('headers')->getValue($http));
        $this->assertEquals([
            'cookie_1' => 'cookie_value_1',
            'cookie_2' => 'cookie_value_2',
        ], $reflection->getProperty('cookies')->getValue($http));
        $this->assertEquals([
            'name' => 'Mehrshad',
            'family' => 'Darzi'
        ], $reflection->getProperty('body')->getValue($http));
    }

    public function test_setupPropertyJsonBody()
    {
        $http = $this->setupGETRequest();
        $http->json([
            'id' => 1,
            'title' => 'Post Title'
        ]);
        $reflection = new \ReflectionClass($http);
        $this->assertEquals(Json::encode([
            'id' => 1,
            'title' => 'Post Title'
        ]), $reflection->getProperty('body')->getValue($http));
    }

    public function test_setParamsBeforeSend()
    {
        $http = $this->setupGETRequest();
        $http->setParams();
        $params = $http->getParams();

        $this->assertNotEmpty($params);
        $this->assertIsArray($params);

        $arrayKeys = [
            'method',
            'timeout',
            'redirection',
            'httpversion',
            'headers',
            'cookies',
            'sslverify',
            'user-agent',
            'body',
        ];
        foreach ($arrayKeys as $key) {
            $this->assertArrayHasKey($key, $params);
        }
        $this->assertSame('GET', $params['method']);
    }

    public function test_responseGetRequest()
    {
        // Setup Fake Cookie Response
        add_filter('http_response', function ($response, $parsed_args, $url) {
            if ($url == 'https://jsonplaceholder.typicode.com/todos/1') {
                $cookies = [];
                $cookies[] = new \WP_Http_Cookie([
                    'name' => 'username',
                    'value' => 'admin'
                ]);
                $cookies[] = new \WP_Http_Cookie([
                    'name' => 'pass',
                    'value' => 'x123'
                ]);
                $response['cookies'] = $cookies;
                return $response;
            }
            return $response;
        }, 999, 3);

        // Request
        $http = new HTTP();
        $http->get('https://jsonplaceholder.typicode.com/todos/1')
            ->timeout(30)
            ->useragent('Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0')
            ->curl(function ($handle) {
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            })
            ->unsafe()
            ->send();

        // Excepted Response
        $response = [
            "userId" => 1,
            "id" => 1,
            "title" => "delectus aut autem",
            "completed" => false
        ];

        // Tests
        $this->assertEquals(200, $http->getStatusCode());
        $this->assertEquals('OK', $http->getReasonPhrase());
        $this->assertIsObject($http->getHeaders());
        $this->assertEquals('application/json; charset=utf-8', $http->getHeader('content-type'));
        $this->assertEquals(true, $http->hasHeader('content-type'));
        $this->assertEquals(false, $http->hasHeader('test-key'));
        $this->assertEquals($response, json_decode($http->getBody(), true));
        $this->assertEquals($response, $http->getJsonBody());
        $this->assertNotEmpty($http->getCookies());
        $this->assertIsArray($http->getCookies());
        $this->assertCount(2, $http->getCookies());
        $this->assertEquals('admin', $http->getCookie('username')->value);
        $this->assertEquals(true, $http->hasCookie('username'));
        $this->assertEquals(false, $http->hasCookie('user_login'));
        $this->assertTrue(has_action('http_api_curl'));
        $this->assertFalse(has_filter('http_request_args'));
    }

    public function test_responsePostRequest()
    {
        $http = new HTTP();
        $http
            ->post('https://jsonplaceholder.typicode.com/posts')
            ->timeout(30)
            ->ssl(false)
            ->json([
                'title' => 'foo',
                'body' => 'bar',
                'userId' => 1,
            ])
            ->headers([
                'Content-type' => 'application/json; charset=UTF-8'
            ])
            ->send();
        $response = [
            'id' => 101,
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1,
        ];

        $this->assertEquals(201, $http->getStatusCode());
        $this->assertEquals('Created', $http->getReasonPhrase());
        $this->assertIsObject($http->getHeaders());
        $this->assertEquals('application/json; charset=utf-8', $http->getHeader('content-type'));
        $this->assertEquals(true, $http->hasHeader('content-type'));
        $this->assertEquals(false, $http->hasHeader('test-key'));
        $this->assertEquals($response, json_decode($http->getBody(), true));
        $this->assertEquals($response, $http->getJsonBody());
        $this->assertEmpty($http->getCookies());
    }

    public function test_PutRequestMethod()
    {
        $http = new HTTP();
        $http->put('https://jsonplaceholder.typicode.com/todos/1');
        $http->setParams();
        $params = $http->getParams();
        $this->assertEquals('PUT', $params['method']);
    }

    public function test_deleteRequestMethod()
    {
        $http = new HTTP();
        $http->delete('https://jsonplaceholder.typicode.com/todos/1');
        $http->setParams();
        $params = $http->getParams();
        $this->assertEquals('DELETE', $params['method']);
    }

    public function test_patchRequestMethod()
    {
        $http = new HTTP();
        $http->patch('https://jsonplaceholder.typicode.com/todos/1');
        $http->setParams();
        $params = $http->getParams();
        $this->assertEquals('PATCH', $params['method']);
    }

    public function test_optionsRequestMethod()
    {
        $http = new HTTP();
        $http->options('https://jsonplaceholder.typicode.com/todos/1');
        $http->setParams();
        $params = $http->getParams();
        $this->assertEquals('OPTIONS', $params['method']);
    }

    public function test_headRequestMethod()
    {
        $http = new HTTP();
        $http->head('https://jsonplaceholder.typicode.com/todos/1');
        $http->setParams();
        $params = $http->getParams();
        $this->assertEquals('HEAD', $params['method']);
    }

    public function test_downloadFile()
    {
        $file = rtrim(home_url(), "/") . '/license.txt';
        $copy = ABSPATH . '/new-license.txt';
        $http = new HTTP();

        $download = $http->download($file, 100, true, false);
        $this->assertFileExists($download->tmp());
        $this->assertIsString($download->tmp());

        $download->copy($copy);
        $this->assertFileExists($copy);
        @unlink($copy);
    }
}