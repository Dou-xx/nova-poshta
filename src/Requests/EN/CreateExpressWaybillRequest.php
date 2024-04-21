<?php

namespace Dou\NovaPoshta\Requests\EN;

use Dou\NovaPoshta\ArrHelper;
use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CreateEnResponse;

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
 * @method self changeInfoRegClientBarcodes(string $value) Добавочная текстовая информация                                                                                                                                                                                                                                                                                                                      Добавочная текстовая информация
 */
class CreateExpressWaybillRequest extends BaseRequest implements RequestContract
{
    /**
     * @var array|string[]
     */
    protected array $requestStructure = [
        'modelName'        => 'InternetDocument',
        'calledMethod'     => 'save',
        'methodProperties' => [
            'PayerType'             => 'Recipient',
            'PaymentMethod'         => 'Cash',
            'DateTime'              => null,
            'CargoType'             => null,
            'CargoDetails'          => null,
            'VolumeGeneral'         => null,
            'Weight'                => null,
            'ServiceType'           => 'WarehouseWarehouse',
            'SeatsAmount'           => '1',
            'Description'           => null,
            'Cost'                  => null,
            'CitySender'            => null,
            'Sender'                => null,
            'SenderAddress'         => null,
            'ContactSender'         => null,
            'SendersPhone'          => null,
            'CityRecipient'         => null,
            'Recipient'             => null,
            'RecipientAddress'      => null,
            'ContactRecipient'      => null,
            'RecipientsPhone'       => null,
            'BackwardDeliveryData'  => null,
            'AdditionalInformation' => null,
            'OptionsSeat'           => null,
            'InfoRegClientBarcodes' => null,
        ],
    ];

    /**
     * Вызов функций change...
     *
     * @param $method
     * @param $arguments
     *
     * @throws \Exception
     *
     * @return $this
     */
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
     * Добавить наложенной платеж
     *
     * @param float $totalOrder - сумма
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
     * {@inheritDoc}
     */
    public function getResponseClass(): ResponseContract
    {
        return new CreateEnResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function send(): CreateEnResponse|ResponseContract
    {
        return $this->run($this);
    }

    /**
     * Set field
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    private function set(string $key, $value): void
    {
        ArrHelper::set($this->requestStructure, $key, $value);
    }

    private function startsWith($haystack, $needles): bool
    {
        if (!is_iterable($needles)) {
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
