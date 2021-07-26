<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class WeatherService
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * @return array
     */
    public function getCoordonates(string $city, string $apiKey)
    {
        try {
            $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&appid=' . $apiKey);
            $results = $response->toArray();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $results;
    }

    /**
     * @return array
     */
    public function getHistoricalWeather(string $city, string $locale, int $dt, array $coordinates, string $apiKey)
    {
        try {
            $response = $this->client->request('GET', 'http://api.openweathermap.org/data/2.5/onecall/timemachine?lat=' . $coordinates['coord']['lat'] . '&lon=' . $coordinates['coord']['lat'] . '&dt=' . $dt . '&lang=' . $locale . '&units=metric&appid=' . $apiKey);
            $results = $response->toArray();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return [$results];
    }

    /**
     * @return array
     */
    public function getWeather(string $city, string $locale, string $apiKey)
    {
        $coordinates = $this->getCoordonates($city, $apiKey);
        for ($i = 1; $i < 6; $i++) {
            $dt = $coordinates['dt'] - $i * 3600 * 24;
            $historicalWeather[] = $this->getHistoricalWeather($city, $locale, $dt, $coordinates, $apiKey);
        }
        $country = (new CountryCode())->getCountry($coordinates['sys']['country']);

        try {
            $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/onecall?lat=' . $coordinates['coord']['lat'] . '&lon=' . $coordinates['coord']['lon'] . '&lang=' . $locale . '&units=metric&appid=' . $apiKey);
            $results = $response->toArray();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        // dd($results, $coordinates, $historicalWeather);
        return [$results, $coordinates, $historicalWeather, $country];
    }
}
