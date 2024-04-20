### Библиотека работает с API Новой Почты
#### Функционал
- Поиск городов (Украина)
- Поиск отделений Новой почты

#### Установка
``` composer require dou-xx/nova-poshta-laravel ```

### Примеры использования
- Поиск городов
```
use Dou\NovaPoshtaApi\Requests\Address\CityRequest
use Dou\NovaPoshtaApi\Service\NovaPoshtaAPI;

...

$request = new CityRequest();
$request = $request->setCityName('Днепр');
$api = new NovaPoshtaAPI();
$api->setRequest($request);
$api->get();

dump($api->response);
```
- В результате выполнения будет возвращен объект класса Response
```
Пример ответа
Dou\NovaPoshtaApi\Response\Response {#253
  -response: array:9 [
    "success" => true
    "data" => array:8 [
      0 => array:20 [
        "Description" => "Дніпрельстан"
        "DescriptionRu" => "Днепрельстан"
        "Ref" => "eb54475c-e5e4-11e9-b48a-005056b24375"
        "Delivery1" => "1"
        "Delivery2" => "0"
        "Delivery3" => "1"
        "Delivery4" => "0"
        "Delivery5" => "1"
        "Delivery6" => "0"
        "Delivery7" => "0"
        "Area" => "7150812f-9b87-11de-822f-000c2965ae0e"
        "SettlementType" => "563ced13-f210-11e3-8c4a-0050568002cf"
        "IsBranch" => "0"
        "PreventEntryNewStreetsUser" => null
        "CityID" => "4462"
        "SettlementTypeDescription" => "село"
        "SettlementTypeDescriptionRu" => "село"
        "SpecialCashCheck" => 1
        "AreaDescription" => "Запорізька"
        "AreaDescriptionRu" => "Запорожская"
```
- Поиск отделений выбранного города

```angular2html
use Dou\NovaPoshtaApi\Requests\Address\WarehousesRequest;
use Dou\NovaPoshtaApi\Service\NovaPoshtaAPI;

...

$request = new WarehousesRequest();
$request = $request->setCityRef('eb54475c-e5e4-11e9-b48a-005056b24375'); // или $request->setCityName('Днепрельстан')

$api = new NovaPoshtaAPI();
$api->setRequest($request);
$api->get();

dump($api->response);
```
```angular2html
Пример ответа

Dou\NovaPoshtaApi\Response\Response
-response: array:9 [
  "success" => true
  "data" => array:1 [
    0 => array:41 [
      "SiteKey" => "22624"
      "Description" => "Пункт приймання-видачі (до 30 кг): вул. Леніна, 54"
      "DescriptionRu" => "Пункт приема-выдачи (до 30 кг), ул. Ленина, 54"
      "ShortAddress" => "Дніпрельстан, Леніна, 54"
      "ShortAddressRu" => "Днепрельстан, Ленина, 54"
      "Phone" => "380800500609"
      "TypeOfWarehouse" => "841339c7-591a-42e2-8233-7a0a00f0ed6f"
      "Ref" => "3294c49f-23ea-11ea-8ac1-0025b502a04e"
      "Number" => "1"
....
```

- Response имеет несколько вспомогательных функций
```
    $api->response->count();        // Возвращает количество найденых элементов (Городов или отделений)
    $api->response->first();        // Возвращает первый элемент
    $api->response->first('Ref');   // Возвращает значение первого элемента, выбранное по ключу
    $api->response->getData();      // Возвращает коллекцию полученных результатов
    $api->response->isSuccess();    // Возвращает true или false, успешен ли был запрос
    $api->response->getResponse();  // Получает данные ответа из новой почты
```

### Больше примеров смотри в папке Tests