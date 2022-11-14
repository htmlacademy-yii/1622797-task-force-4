<?php

namespace app\services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use taskforce\exception\GeocoderException;
use taskforce\exception\JsonErrorException;
use yii\helpers\ArrayHelper;

class GeocoderService
{
    public const RESPONSE_CODE_OK = 200;
    public const GEOCODE_COORDINATES_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    public const GEOCODER_ADDRESS_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
    public const GEOCODER_CITY_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.description';
    public const GEOCODE_LONGITUDE = 0;
    public const GEOCODE_LATITUDE = 1;

    /**
     * @param string $address
     * @return array
     * @throws GeocoderException
     * @throws GuzzleException
     * @throws JsonErrorException
     */
    public function loadLocation(string $address): array
    {
        $apiUrl = 'https://geocode-maps.yandex.ru';
        $client = new Client(['base_uri' => $apiUrl]);

        $response = $client->request('GET', '1.x', [
            'query' => [
                'apikey' => 'a3f42aa0-e7e6-4132-bec6-856c58de44df',
                'geocode' => $address,
                'format' => 'json'
            ]
        ]);

        if ($response->getStatusCode() !== self::RESPONSE_CODE_OK) {
            throw new GeocoderException();
        }

        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonErrorException();
        }
        return $responseData;
    }

    /**
     * @param string $address
     * @return string
     * @throws GeocoderException
     * @throws GuzzleException
     * @throws JsonErrorException
     * @throws Exception
     */
    public function getLatitude(string $address): string
    {
        $location = explode(' ', ArrayHelper::getValue($this->loadLocation($address), self::GEOCODE_COORDINATES_KEY));
        return $location[self::GEOCODE_LATITUDE];
    }

    /**
     * @param string $address
     * @return string
     * @throws GeocoderException
     * @throws GuzzleException
     * @throws JsonErrorException
     * @throws Exception
     */
    public function getLongitude(string $address): string
    {
        $location = explode(' ', ArrayHelper::getValue(
            $this->loadLocation($address),
            self::GEOCODE_COORDINATES_KEY
        ));
        return $location[self::GEOCODE_LONGITUDE];
    }

    /**
     * @param string $address
     * @return string
     * @throws GeocoderException
     * @throws GuzzleException
     * @throws JsonErrorException
     * @throws Exception
     */
    public function getAddress(string $address): string
    {
        return ArrayHelper::getValue($this->loadLocation($address), self::GEOCODER_ADDRESS_KEY);
    }

    /**
     * @param string $address
     * @return string
     * @throws GeocoderException
     * @throws GuzzleException
     * @throws JsonErrorException
     * @throws Exception
     */
    public function getCity(string $address): string
    {
        return ArrayHelper::getValue($this->loadLocation($address), self::GEOCODER_CITY_KEY);
    }
}
