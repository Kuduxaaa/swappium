<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

/**
 * The Unistream class provides methods for making requests to the Unistream API.
 */
class Unistream {
    private $applicationId;
    private $applicationSecret;
    static string $baseUrl = 'https://slt-test.api.unistream.com/v1/';

    /**
     * Create a new Unistream instance.
     *
     * @param string $applicationId The application ID for the Unistream API.
     * @param string $applicationSecret The application secret for the Unistream API.
     */
    public function __construct($applicationId, $applicationSecret) {
        $this->applicationId = $applicationId;
        $this->applicationSecret = $applicationSecret;
    }


    /**
     * Make a request to the Unistream API.
     *
     * @param string $method The HTTP method to use for the request (e.g. GET, POST, etc.).
     * @param string $url The URL to make the request to.
     * @param array|null $data The data to include in the request (if any).
     *
     * @return \Illuminate\Http\Client\Response The response from the Unistream API.
     */
    public function makeRequest($method, $url, $data = null) {
        $headers = [
            'Content-Type' => 'application/json',
            'X-Unistream-Application-Id' => $this->applicationId
        ];

        $url = self::$baseUrl . $url;
        $stringRepresentation = $this->toStringRepresentation($method, $url, $data);
        $signature = base64_encode(hash_hmac('sha256', $stringRepresentation, base64_decode($this->applicationSecret), true));
        $authorization = 'UNIHMAC ' . $this->applicationId . ':' . $signature;
        $headers['Authorization'] = $authorization;

        $response = Http::withHeaders($headers)->$method($url, $data);

        return $response;
    }


    /**
     * Get the string representation of the fields that need to be hashed for the request.
     *
     * @param string $method The HTTP method being used for the request.
     * @param string $url The URL being used for the request.
     * @param array|null $data The data being included in the request (if any).
     *
     * @return string The string representation of the fields to be hashed for the request.
     */

    private function toStringRepresentation($method, $url, $data) {
        $contentMD5 = '';
        if ($data) {
            $contentMD5 = md5(json_encode($data), true);
            $contentMD5 = base64_encode($contentMD5);
        }

        $date = gmdate('D, d M Y H:i:s T');
        $pathAndQuery = parse_url($url, PHP_URL_PATH) . '?' . parse_url($url, PHP_URL_QUERY);
        $pathAndQuery = strtolower(urldecode($pathAndQuery));

        $headers = [
            'Content-MD5' => $contentMD5,
            'Date' => $date
        ];

        foreach ($headers as $headerName => $headerValue) {
            if (!$headerValue) {
                unset($headers[$headerName]);
            }
        }

        $headers['X-Unistream-Application-Id'] = $this->applicationId;

        ksort($headers, SORT_STRING);

        $headerStrings = [];
        foreach ($headers as $headerName => $headerValue) {
            $headerStrings[] = strtolower($headerName) . ':' . $headerValue;
        }

        $headerString = implode("\n", $headerStrings);

        $result = $method . "\n" . $contentMD5 . "\n" . $headerString . "\n" . $pathAndQuery;

        return $result;
    }
}
