# Nova Poshta (Документация в процессе написания)
### PHP библиотека для работы с API Новой Почты

------
### Основные возможности:
- Получение данных и контакт отправителя
- Создание/Получение данных и контакт получателя (Физ. и Юр. лицо)
- Создание адреса получателя
- Создание EN документа (ТТН)

### Установка
`composer require dou-xx/nova-poshta`

### Примеры кода:

Важно! Для работы нужен Api Key Новой Почты
#### Получение отправителя:
Класс GetSenderRequest  
Результат - объект класса ResponseContract

```
use Dou\NovaPoshta\Requests\Counterparty\GetSenderRequest;
    ....
        $apiKey = '78f19724b0......9b1541c0a';
        $senderRequest = new GetSenderRequest($apiKey);
        $response = $senderRequest->send();

        dump($response->isSuccess());          // true or false
        dump($response->getItem('FirstName')); // Приватна особа or null
        dump($response->getItem('Ref'));       // 55d96953-528d-0000-0000-005056881c6b or null
        dump($response->getData());            // Весь массив ответа Новой Почты, или []
```

Из примера выше видно, что $response имеет свои методы для получения данных

`$response->isSuccess()` - возвращает bool (true или false), и говорит об успешности запроса в целом

`$response->getData()` - получить весь массив данных ответа Новой Почты

`$response->getItem()` - может принимать 2 параметра:
- Первый это ключ в массиве полученных данных
- Второй это номер элемента (индекс) полученного массива.
  В ответе на запрос контактов отправителя элементов может быть несколько.

Примеры:  
`$response->getItem('Ref')` - Получить значение Ref из первого элемента  
`$response->getItem('Ref', 1)` - Получить значение Ref из второго элемента  
`$response->getItem()` - Получить весь массив данных первого элемента  
`$response->getItem(null, 1)` - Получить весь массив данных второго элемента

----

#### Получить контакт отправителя:
Класс GetCounterPartyContactsRequest  
Результат - объект класса ResponseContract

```
use Dou\NovaPoshta\Requests\Counterparty\GetCounterPartyContactsRequest;

        // это идентификатор отправителя из прошлого примера
        $ref = $response->getItem('Ref');
        
        $senderContactRequest = new GetCounterPartyContactsRequest($apiKey);
        $response = $senderContactRequest->setRef($ref)->send();
        
        dump($response->isSuccess());
        dump($response->getItem('Ref'));
        dump($response->getItem('LastName'));
        dump($response->getItem('FirstName'));
        dump($response->getItem('MiddleName'));
        dump($response->getItem('Phones'));
        dump($response->getItem('Description'));
```

----

#### Создать получателя (Физ-лицо):
Класс CreateCounterPartyRequest  
Результат - объект класса CounterPartyResponse  
Этот запрос создает получателя, или если он уже существует - вернет его  
Так-же в ответе уже есть и контактное лицо получателя

```
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyRequest;

        $recipient = new CreateCounterPartyRequest($apiKey);
        
        // Отчество и Email - не обязательны
        $recipient->setFields('380987776655', 'Имя', 'Фамилия', 'Отчество', 'email@com.ua');
        $response = $recipient->send();
        
        dump($response->getCounterPartyRef()); // Ref - контрагента получателя
        dump($response->getContactPersonRef()); // Ref - контакта получателя
```

----

#### Создать получателя (Организация, Юр-лицо)
Класс CreateCounterPartyJuristicRequest  
Результат - объект класса CounterPartyResponse  
Для создания получателя нужно передать Код ЄДРПОУ организации получателя

```
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyJuristicRequest;
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyContactRequest;

        // Получатель организация
        $recipient = new CreateCounterPartyJuristicRequest($apiKey);
        $recipient->setFields('0000000001'); // Код ЄДРПОУ
        $response = $recipient->send();
        dump($response->getCounterPartyRef()); // Ref - контрагента получателя

        $ref = $response->getCounterPartyRef();
        $recipient = new CreateCounterPartyContactRequest($apiKey);
        $recipient->setFields($ref, '380987776655', 'Имя', 'Фамилия', 'Отчество', 'email@com.ua');
        $response = $recipient->send();
        
        dump($response->getContactPersonRef()); // Ref - контакта получателя (огранизации)
```

----

#### Создать адрес получателя
Класс CreateAddressRequest  
Результат - объект класса CounterPartyResponse

```
        $ref = $response->getCounterPartyRef();
        $cityRef = '4a579385-413e-11dd-9198-001d60451983';

        $recipient = new CreateAddressRequest($apiKey);
        // тут передается $ref - Ref получателя, $cityRef - Ref улицы из справочника новой почты, Номер дома, номер квартиры
        $recipient->setFields($ref, $cityRef, 14, 2);
        $response = $recipient->send();
        dump($response->getItem('Ref'));         // Ref созданного адреса
        dump($response->getItem('Description')); // Адрес текстом (строкой)
```

---- 
#### Создать накладную (ТТН):
Класс CreateExpressWaybillRequest
- Есть множество методов передачи данные, каждый метод начинается со слова `change`
- Поддерживаемые методы соответствуют документации Новой Почты


```
 $createRequest = new CreateExpressWaybillRequest($apiKey);
 $createRequest->changePayerType('Recipient');                // Установить кто оплачивает доставку: 'Recipient' - получатель, 'Sender' - отправитель
 $createRequest->changeCargoType('Parcel')                    // Тип посылки: может быть 'Parcel', 'Pallets', 'Documents'
```

Весь список методов:

```
/**
 * @method self changePayerType(string $value)             Кто оплачивает доставку: 'Recipient', 'Sender'
 * @method self changePaymentMethod(string $value)         Тип оплаты доставки: 'Cash', 'NonCash'
 * @method self changeDateTime(string $value)              Дата отправки: Дата когда вы планируете привезти посылку на отделение для отправки. 
 * @method self changeCargoType(string $value)             Тип груза: 'Parcel' - посылка, 'Pallets' - паллеты, 'Documents' - документы (пакет)
 * @method self changeVolumeGeneral(string $value)         Объем общий, м.куб
 * @method self changeWeight(string $value)                Вес фактический, кг
 * @method self changeServiceType(string $value)           Технология доставки: WarehouseWarehouse - с отделения на отделение, WarehouseDoors - курьером
 * @method self changeSeatsAmount(string $value)           Количество мест отправления
 * @method self changeDescription(string $value)           Описание
 * @method self changeCost(string $value)                  Объявленная стоимость
 * @method self changeCitySender(string $value)            Город отправителя: Ref из стравочника городов
 * @method self changeSender(string $value)                Контрагент отправитель: Ref отправителя
 * @method self changeSenderAddress(string $value)         Адреса отправителя: Ref отделения новой почты, от куда будет отправлена посылка
 * @method self changeContactSender(string $value)         Контактное лицо отправителя: Ref контакта отправителя
 * @method self changeSendersPhone(string $value)          Телефон отправителя
 * @method self changeCityRecipient(string $value)         Город получателя: Ref из стравочника городов
 * @method self changeRecipient(string $value)             Получатель: Ref Получателя
 * @method self changeRecipientAddress(string $value)      Адрес получателя: Ref отделения или адреса куда нужно доставить посылку
 * @method self changeContactRecipient(string $value)      Контактное лицо получателя: Ref контакта
 * @method self changeRecipientsPhone(string $value)       Телефон получателя
 * @method self changeAdditionalInformation(string $value) Добавочная текстовая информация
 * @method self changeInfoRegClientBarcodes(string $value) Добавочная текстовая информация
 */
```

Дополнительные функции:
- Добавить услугу наложенного платежа `$createRequest->cashBack(500);` - наложенный платеж
- Добавить контроль оплат `$createRequest->enableCashControl(500)` - Используется если есть договор с Новой Почтой
- Добавить габариты и вес посылки (места) `addOptionsSeat(float $width, float $height, float $length, float $weight = null)`  
  Пример: 20 x 40 x 15 см, 3кг `$createRequest->addOptionsSeat(20, 40, 15, 3)`
- Получить количество мест `$createRequest->getOptionsSeatsCount()`

----

### Примеры запроса создания ТТН
Создание наложенного платежа
```
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyContactRequest;
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyRequest;
use Dou\NovaPoshta\Requests\Counterparty\GetCounterPartyContactsRequest;
use Dou\NovaPoshta\Requests\Counterparty\GetSenderRequest;
use Dou\NovaPoshta\Requests\EN\CreateExpressWaybillRequest;

public function createEN()
    {
        $apiKey = '78f19724b096.....0c19b1541c0a';

        // 1. Получаем отправителя
        $senderRequest = new GetSenderRequest($apiKey);
        $senderResponse = $senderRequest->send();

        // 1.1 Получаем контакт отправителя (из может быть несколько в аккаунте Новой почты, мы просто возьмем первого)
        $senderContactRequest = new GetCounterPartyContactsRequest($apiKey);
        $senderContactResponse = $senderContactRequest
            ->setRef($senderResponse->getItem('Ref'))
            ->send();


        // 2. Создаем или получаем получателя посылки
        $recipientRequest = new CreateCounterPartyRequest($apiKey);

        $recipientRequest->setFields('380987776655', 'Тест', 'Тестов');
        $recipientResponse = $recipientRequest->send();

        // 3. Данные
        // 3.1 Данные отправителя
        $senderRef = $senderResponse->getItem('Ref');
        $senderContactRef = $senderContactResponse->getItem('Ref');
        $citySenderRef = '03d352e4-47b8-11e4-acce-0050568002cf';
        $branchSenderRef = '03d352f6-47b8-11e4-acce-0050568002cf';

        // 3.2 Данные получателя
        $recipientRef = $recipientResponse->getCounterPartyRef();
        $recipientContactRef = $recipientResponse->getContactPersonRef();
        $cityRecipientRef = '03d352e4-47b8-11e4-acce-0050568002cf';
        $branchRecipientRef = '03d352f6-47b8-11e4-acce-0050568002cf';

        // 4. Создаем запрос на создание ТТН
        $createRequest = new CreateExpressWaybillRequest($apiKey);
        $createRequest->changePayerType('Recipient');           // Кто платит за доставку - получатель
        $createRequest->changePaymentMethod('Cash');            // Тип оплаты доставки - наличные
        $createRequest->changeCargoType('Parcel');              // Тип груза - посылка
        $createRequest->changeWeight(2);                        // Вес фактический, кг. - 2кг
        $createRequest->changeSeatsAmount(1);                   // Количество мест отправления - одно место
        $createRequest->changeDescription('Тест создания ТТН через API');      // Описание посылки
        $createRequest->changeCost(250);                        // Объявленная стоимость посылки - 250 грн
        // Данные отправителя
        $createRequest->changeSender($senderRef);                     // Ref Контрагента отправитель
        $createRequest->changeContactSender($senderContactRef);       // Ref контактного лица отправителя
        $createRequest->changeCitySender($citySenderRef);             // Ref Города отправителя
        $createRequest->changeSenderAddress($branchSenderRef);        // Ref адреса (отделение) отправителя
        $createRequest->changeSendersPhone('380979999999');           // Телефон отправителя
        // Данные получателя
        $createRequest->changeRecipient($recipientRef);               // Ref Контрагента получателя
        $createRequest->changeContactRecipient($recipientContactRef); // Ref контактного лица получателя
        $createRequest->changeCityRecipient($cityRecipientRef);       // Ref города получателя
        $createRequest->changeRecipientAddress($branchRecipientRef);  // Ref адреса (отделения) получателя
        $createRequest->changeRecipientsPhone('380998888888');        // Телефон получателя

        $createRequest->cashBack(500);                            // Устанавливаем размер наложенного платежа 500 грн.
        $createRequest->changeInfoRegClientBarcodes('Заказ-001'); // Добавляем свой номер заказа, он будет виден в накладной (необязательно)

        $response = $createRequest->send();

        dump($response->isSuccess());                // true
        dump($response->getTTN());                   // 20450910916240
        dump($response->getDeliveryCost());          // 25
        dump($response->getEstimatedDeliveryDate()); // 21.04.2024
        dump($response->getRef());                   // 3a8befe9-ff47-11ee-a9e3-48df37b921da - ID документа в системе Новой Почты
    }

```

----

### Трекинг ТТН
Получение данных о состоянии доставки  
Класс: TrackingRequest

```
    public function tracking() {
        $apiKey = '78f19724b096.....c19b1541c0a';

        $trackingRequest = new TrackingRequest($apiKey);
        $trackingRequest->setSenderPhone('380979998877');
        $trackingRequest->setTTNs([
            '20450910916240',
            '20450910817793',
            '20450910815791'
        ]);

        $response = $trackingRequest->send();
        dump($response->getData());
    }
```
