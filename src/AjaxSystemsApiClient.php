<?php

namespace Mukhin\AjaxSystemsApi;

use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Mukhin\AjaxSystemsApi\Exception\AuthenticationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AjaxSystemsApiClient
 * @package Mukhin\AjaxSystemsApi
 */
class AjaxSystemsApiClient
{
    const API_BASE_URL = 'https://app.ajax.systems';

    const ARM_STATE_DISARMED  = 0;
    const ARM_STATE_ARMED     = 1;
    const ARM_STATE_PARTIAL   = 2;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * AjaxSystemsApiClient constructor.
     * @param MessageFactory|null $messageFactory
     * @param HttpClient|null $httpClient
     */
    public function __construct(?MessageFactory $messageFactory = null, ?HttpClient $httpClient = null)
    {
        if (!$messageFactory) {
            $messageFactory = MessageFactoryDiscovery::find();
        }

        if (!$httpClient) {
            $httpClient = HttpClientFactory::create();
        }

        $this->messageFactory = $messageFactory;
        $this->httpClient = $httpClient;
    }

    /**
     * You should use cookie plugin to store cookie
     *
     * @param string $login
     * @param string $password
     */
    public function login(string $login, string $password)
    {
        $response = $this->call('api/account/do_login', [
            'j_username'=>$login,
            'j_password'=>$password
        ]);

        if (!in_array($response->getStatusCode(), [302, 200])) {
            throw new AuthenticationException(sprintf(
                'Authentification failure. Reason: %s',
                $response->getReasonPhrase()
            ));
        }
    }

    /**
     * @return bool
     */
    public function getCsaConnection()
    {
        $response = $this->call('SecurConfig/api/account/getCsaConnection');
        $this->validateResponse($response);
    }

    /**
     * @param integer $action self::ARM_STATE_*
     * @param string $hexHubId Hex representations of Hub ID, e.g. 00001234
     * @return bool
     */
    public function setArm(int $action, string $hexHubId)
    {
        $response = $this->call('SecurConfig/api/dashboard/setArm', [
            'hubID'  => $hexHubId,
            'action' => $action
        ]);
        $json = $this->decodeResponse($response);
        return ($action == $json['state']);
    }

    /**
     * @param string $hexHubId Hex representations of Hub ID, e.g. 00001234
     * @return bool
     */
    public function sendPanic(string $hexHubId)
    {
        $response = $this->call('SecurConfig/api/dashboard/sendPanic', [
            'hubID' => $hexHubId
        ]);
        return $this->decodeResponse($response, $hexHubId);
    }

    /**
     * @param string $hexHubId Hex representations of Hub ID, e.g. 00001234
     * @return string
     */
    public function getHubBalance(string $hexHubId): string
    {
        $response = $this->call(sprintf(
            'SecurConfig/api/dashboard/getHubBalance?hubID=%s',
            $hexHubId
        ));
        return $this->decodeResponse($response, 'data');
    }

    /**
     * Returns RAW logs data
     *
     * @param string $hexHubId Hex representations of Hub ID, e.g. 00001234
     * @param int $count Max count of log items to return
     * @param int $offset Offset
     * @return array
     */
    public function getRawLogs(string $hexHubId, int $count = 10, int $offset = 0)
    {
        $response = $this->call('SecurConfig/api/dashboard/getLogs', [
            'hubId'=>$hexHubId,
            'count'=>$count,
            'offset'=>$offset,
        ]);
        return $this->decodeResponse($response, 'data');
    }

    /**
     * @param ResponseInterface $response
     * @throws \Exception
     */
    protected function validateResponse(ResponseInterface $response)
    {
        if (!in_array($response->getStatusCode(), [200, 302])) {
            throw new \Exception(sprintf(
                'Bad status code: %s %s',
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }
    }

    /**
     * @param ResponseInterface $response
     * @param null|string $jsonDataKey Key of json payload that should be decoded
     * @return mixed
     * @throws \Exception
     */
    protected function decodeResponse(ResponseInterface $response, ?string $jsonDataKey = null)
    {
        $this->validateResponse($response);
        $content = $response->getBody()->getContents();

        if (!$content) {
            throw new \Exception('Empty response');
        }

        $json = json_decode($content, true);

        if (!$json['requestResult']) {
            throw new \Exception(sprintf(
                'Error in user response: %s',
                isset($json['message']) && $json['message'] ?: $content
            ));
        }

        if ($jsonDataKey) {
            if (!is_array($json[$jsonDataKey])) {
                $subJson = json_decode($json[$jsonDataKey], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $subJson;
                }
            }

            $json = $json[$jsonDataKey];
        }

        return $json;
    }

    /**
     * @param string $relativeUrl
     * @param array $formData
     * @param string $method
     * @return ResponseInterface
     */
    protected function call(string $relativeUrl, array $formData = [], string $method = 'POST')
    {
        $headers = [];
        $formData && $headers += ['Content-Type'=>'application/x-www-form-urlencoded;charset=UTF-8'];

        $request = $this->messageFactory->createRequest(
            $method,
            $this->getAbsoluteUrl($relativeUrl),
            $headers,
            $this->serializeFormData($formData)
        );

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @param $formData
     * @return array|null|string
     */
    protected function serializeFormData($formData)
    {
        if (!$formData) {
            return null;
        }

        if (!is_array($formData)) {
            return $formData;
        }

        array_walk($formData, function (&$value, &$key) {
            if ($value === true) {
                $value = 'true';
            }
            else if ($value === false) {
                $value = 'false';
            }
        });

        return http_build_query($formData);
    }

    /**
     * @param string $relatedUrl
     * @return string
     */
    protected function getAbsoluteUrl(string $relativeUrl): string
    {
        return sprintf(
            '%s/%s',
            self::API_BASE_URL,
            ltrim($relativeUrl, '/')
        );
    }

}