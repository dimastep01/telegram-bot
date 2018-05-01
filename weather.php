<?php
	use GuzzleHttp\Client;
class Weather
{

	protected $token = "dcf42edbbea972d8f61a7cb53acc8ee6";

	public function getWeather($lat, $lon)
	{

		$url = "http://api.openweathermap.org/data/2.5/weather";

		$result = file_get_contents($url.'?APPID=' . $this->token . '&lat='.$lat . '&lon=' . $lon);

		return json_decode($result);
	}
}
