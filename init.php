<?php

include('vendor/autoload.php');

include('TelegramBot.php');
include('weather.php');
//include('city.php');

//Получаем сообщения
$telegramApi = new TelegramBot();

$weatherApi = new Weather();



while (true) {
	static $result;
	sleep(2);

	$updates = $telegramApi->getUpdates();


	//Пробегаемся по каждому сообщению
		foreach ($updates as $update) {
	

		 if (strpos($update->message->text, "Погода: ") !== false) {
           $city = str_replace("Погода: ", "", $update->message->text);
		   $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$city"."&key=AIzaSyDJO0CsRVakiFZG2cyuV7xjSWZvZD9o8Ao");
		   $decoded = json_decode($json, true);
		   $lat = $decoded['results'][0]['geometry']['location']['lat'];
		   $lon = $decoded['results'][0]['geometry']['location']['lng'];
           

           $result = $weatherApi->getWeather($lat, $lon);
           
           $temp = $result->main->temp - 273;
           $wind = $result->wind->speed;
           echo "Request  \n";

           switch ($result->weather[0]->main) {
			case "Clear" :
				$response = "На улице безоблачно. Погода отличная. Хорошего дня!";
				break;
			case "Clouds" :
				$response = "На улице облачно, На всякий случай захватите зонтик. Удачи!";
				break;
			case "Rain" :
				$response = "На улице дождь. Возьмите зонтик и теплое настроение! :)";
				break;
			default:
				$response = "Мне не удается вычислить погоду, посмотрите в окно.";
			}

			$response .= "\n Температура: $temp градусов С. \n Скорость ветра: $wind м/с";

			$telegramApi->sendMessage($update->message->chat->id, $response);

       } else if($update->message->text == "Анекдот") {
       	$anekdots = json_decode(file_get_contents('http://umorili.herokuapp.com/api/random?num=100'), true);
		$response = strip_tags(html_entity_decode($anekdots[rand(0,99)]['elementPureHtml']));
       	$telegramApi->sendMessage($update->message->chat->id, $response);
       } else if(strpos($update->message->text, "Когда я ") !== false) {
       	$response = "Это произойдёт ".date('d.m.Y',time() + 60*60*24 * rand(0, (int) (10000)));; 
       	$telegramApi->sendMessage($update->message->chat->id, $response);
       } else if(strpos($update->message->text, "Помощь") !== false) {
       	$response = "Вот что я могу: \n Погода: (город)  - показываю погоду \n Анекдот - шучу \n Когда я (стану богатым/перестану лениться/еще что-нибудь) - предсказываю будущее"; 
       	$telegramApi->sendMessage($update->message->chat->id, $response);
       }
        else {
           //Отвечаем на каждое сообщение
           $telegramApi->sendMessage($update->message->chat->id, 'Напишите "Помощь"');
           echo "invalid command \n";
       }





}
}




?>
