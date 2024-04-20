<?php

namespace Dou\NovaPoshta\Requests\EN;

use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyListResponse;
use Dou\NovaPoshta\ArrHelper;

/**
 * @method self changePayerType(string $value)             кто платит
 * @method self changePaymentMethod(string $value)         тип оплаты
 * @method self changeDateTime(string $value)              дата отправки
 * @method self changeCargoType(string $value)             Тип груза
 * @method self changeVolumeGeneral(string $value)         Объем общий, м.куб
 * @method self changeWeight(string $value)                Вес фактический, кг
 * @method self changeServiceType(string $value)           Технология доставки
 * @method self changeSeatsAmount(string $value)           количество мест отправления
 * @method self changeDescription(string $value)           описание
 * @method self changeCost(string $value)                  объявленная стоимость
 * @method self changeCitySender(string $value)            город отправителя
 * @method self changeSender(string $value)                Контрагент отправитель
 * @method self changeSenderAddress(string $value)         адреса отправителя
 * @method self changeContactSender(string $value)         контактного лица отправителя
 * @method self changeSendersPhone(string $value)          телефон отправителя
 * @method self changeCityRecipient(string $value)         город получателя
 * @method self changeRecipient(string $value)             получатель
 * @method self changeRecipientAddress(string $value)      адреса получателя
 * @method self changeContactRecipient(string $value)      контактного лица получателя
 * @method self changeRecipientsPhone(string $value)       телефон получателя
 * @method self changeAdditionalInformation(string $value) добавочная текстовая информация
 * @method self changeInfoRegClientBarcodes(string $value) добавочная текстовая информация
 */
class CreateExpressWaybillRequest extends BaseRequest implements RequestContract
{
    /*
        apiKey*	string[36]	Ваш ключ API 2.0
        modelName*	string	Имя модели
        calledMethod*	string	Имя вызываемого метода
        methodProperties	string	Свойства метода
        PayerType*	string[36]	значение из справочника Тип плательщика
        PaymentMethod*	string[36]	Значение из справочника Форма оплаты
        DateTime*	string[36]	Дата отправки в формате дд.мм.гггг
        CargoType*	string[36]	Значение из справочника Тип груза
        VolumeGeneral	int[36]	Объем общий, м.куб (min - 0.0004), обязательно для заполнения, если не указаны значения OptionsSeat
        Weight*	int[36]	min - 0,1 Вес фактический, кго
        ServiceType*	string[36]	Значение из справочника Технология доставки
        SeatsAmount*	string[36]	Целое число, количество мест отправления
        Description*	string[50]	Текстовое поле, вводиться для доп. описания
        Cost*	int[36]	Целое число, объявленная стоимость
        CitySender*	string[36]	Идентификатор города отправителя
        Sender*	string[36]	Идентификатор отправителя
        SenderAddress*	string[36]	Идентификатор адреса отправителя
        ContactSender*	string[36]	Идентификатор контактного лица отправителя
        SendersPhone*	int[36]	Телефон отправителя в формате: +380660000000, 380660000000, 0660000000
        CityRecipient*	string[36]	Идентификатор города получателя
        Recipient*	string[36]	Идентификатор получателя
        RecipientAddress*	string[36]	Идентификатор адреса получателя
        ContactRecipient*	string[36]	Идентификатор контактного лица получателя
        RecipientsPhone*	int[36]	телефон получателя в формате: +380660000000, 80660000000, 0660000000
     * */
    /**
     * @var array|string[]
     */
    protected array $requestStructure = [
        'modelName'        => 'InternetDocument',
        'calledMethod'     => 'save',
        'methodProperties' => [
            'PayerType'             => 'Recipient',                              // кто платит
            'PaymentMethod'         => 'Cash',                                   // тип оплаты
            'DateTime'              => null,                                     // дата отправки
            'CargoType'             => null,                                     // Тип груза
            'CargoDetails'          => null,                                     // Массив данных о палетах
            'VolumeGeneral'         => null,                                     // Объем общий, м.куб
            'Weight'                => null,                                     // Вес фактический, кго
            'ServiceType'           => 'WarehouseWarehouse',                     // Технология доставки
            'SeatsAmount'           => '1',                                      // количество мест отправления
            'Description'           => null,                                     // описание
            'Cost'                  => null,                                     // объявленная стоимость
            'CitySender'            => null,                                     // город отрпавителя
            'Sender'                => null,                                     // Контрагент отправитель
            'SenderAddress'         => null,                                     // адреса отправителя
            'ContactSender'         => null,                                     // контактного лица отправителя
            'SendersPhone'          => null,                                     // телефон отправителя
            'CityRecipient'         => null,                                     // город получателя
            'Recipient'             => null,                                     // получатель
            'RecipientAddress'      => null,                                     // адреса получателя
            'ContactRecipient'      => null,                                     // контактного лица получателя
            'RecipientsPhone'       => null,                                     // телефон получателя
            'BackwardDeliveryData'  => null,                                     // данные наложенного платежа
            'AdditionalInformation' => null,                                     // добавочная текстовая информация
            'OptionsSeat'           => null,                                     // информация о размерах мест
            'InfoRegClientBarcodes' => null,
        ],
    ];

    public function __call($method, $arguments): self
    {
        if ($this->startsWith($method, 'change')) {
            $field = 'methodProperties.' . ltrim($method, 'change');

            if (ArrHelper::has($this->requestStructure, $field)) {
                $this->set($field, ...$arguments);
            } else {
                throw new \Exception("Key {$field} not exists");
            }
        }

        return $this;
    }

    /**
     * @param float $totalOrder
     *
     * @return $this
     */
    public function cashBack(float $totalOrder): self
    {
        $this->set('methodProperties.BackwardDeliveryData', [[
            'PayerType'        => 'Recipient',
            'CargoType'        => 'Money',
            'RedeliveryString' => $totalOrder,
        ]]);

        return $this;
    }

    /**
     * Включить контроль оплаты
     *
     * @param float $totalOrder
     *
     * @return $this
     */
    public function enableCashControl(float $totalOrder): self
    {
        $this->set('methodProperties.AfterpaymentOnGoodsCost', $totalOrder);

        return $this;
    }

    /**
     * Добавить место
     *
     * @param float      $width
     * @param float      $height
     * @param float      $length
     * @param null|float $weight
     *
     * @return $this
     */
    public function addOptionsSeat(float $width, float $height, float $length, float $weight = null): self
    {
        $volume = ($width * $length * $height) / 1000000;
        $weight = $weight ?: $volume * 250;

        if ($weight < 0.1) {
            $weight = 0.1;
        }

        $optionSeat = ArrHelper::get($this->requestStructure, 'methodProperties.OptionsSeat');
        $optionSeat[] = [
            'volumetricWidth'  => $width,
            'volumetricLength' => $length,
            'volumetricHeight' => $height,
            'weight'           => $weight,
            'volumetricVolume' => $volume,
        ];

        $this->set('methodProperties.OptionsSeat', $optionSeat);

        return $this;
    }

    /**
     * Получить количество мест
     *
     * @return int
     */
    public function getOptionsSeatsCount(): int
    {
        $optionSeats = ArrHelper::get($this->requestStructure, 'methodProperties.OptionsSeat', []);

        return is_array($optionSeats) ? count($optionSeats) : 0;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->requestStructure;
    }

    /**
     * Set field
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    private function set(string $key, $value): self
    {
        ArrHelper::set($this->requestStructure, $key, $value);

        return $this;
    }

    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyListResponse(); // TODO
    }

    private function startsWith($haystack, $needles): bool
    {
        if (! is_iterable($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && str_starts_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
