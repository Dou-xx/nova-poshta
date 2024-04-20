# Nova Poshta (Документация в процессе написания)
### PHP библиотека для работы с API Новой Почты

------
### Основные возможности:
- Получение данных и контакт отправителя
- Создание/Получение данных и контакт получателя (Физ. и Юр. лицо)
- Создание адреса получателя
- Создание EN документа (ТТН)

### Примеры кода:

Важно! Для работы нужен Api Key Новой Почты
Получение отправителя:  
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

`$response->isSuccess()` - возвращает bool (true или false), и говорит о успешности запроса в целом  

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

Получить контакт отправителя:  
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

Создать получателя (Физ-лицо):  
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

Создать получателя (Организация, Юр-лицо)  
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

Создать адрес получателя  

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