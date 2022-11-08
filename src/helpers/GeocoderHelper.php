<?php

namespace taskforce\helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use taskforce\exception\GeocoderException;
use yii\helpers\ArrayHelper;

class GeocoderHelper
{
    public const GEOCODER_RESPONSE_OK = 200;
    public const GEOCODE_COORDINATE_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    public const GEOCODER_CITY_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.description';
    public const GEOCODER_ADDRESS_KEY = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
    public const GEOCODE_LONGITUDE_KEY = 0;
    public const GEOCODE_LATITUDE_KEY = 1;

    /**
     * @throws GuzzleException
     */
    private static function requestGeocoder(string $location): object
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/'
        ]);

        $query = [
            'apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
            'geocode' => $location,
            'format' => 'json',
        ];
        return $client->request('GET', '1.x', ['query' => $query]);
    }

    /**
     * @throws GuzzleException
     * @throws GeocoderException
     * @throws Exception
     */
    public static function getCoordinates(string $location): array
    {
        $response = self::requestGeocoder($location);

        if ($response->getStatusCode() !== self::GEOCODER_RESPONSE_OK) {
            throw new GeocoderException();
        }

        $body = $response->getBody();
        $responseResult = json_decode($body);
        $coordinates = ArrayHelper::getValue($responseResult, self::GEOCODE_COORDINATE_KEY);

        return explode(' ', $coordinates);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @return array
     * @throws GeocoderException
     * @throws GuzzleException
     */
    public static function getAddress(float $longitude, float $latitude): array
    {
        $location = $longitude . ',' . $latitude;
        $response = self::requestGeocoder($location);

        if ($response->getStatusCode() !== self::GEOCODER_RESPONSE_OK) {
            throw new GeocoderException();
        }

        $body = $response->getBody();
        $responseResult = json_decode($body);

        return ['city' => ArrayHelper::getValue(
            $responseResult,
            self::GEOCODER_CITY_KEY
        ),
            'address' => ArrayHelper::getValue(
                $responseResult,
                self::GEOCODER_ADDRESS_KEY
            )];
    }
}
