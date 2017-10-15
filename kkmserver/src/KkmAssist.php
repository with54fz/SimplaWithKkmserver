<?php
/**
 * (c) http://with54fz.ru/ , email: main@with54fz.ru
 */
require_once(dirname(__FILE__) . '/../config.php');
// если нет функции в конфиге или отладка в файл отключена
if (!function_exists('debuglog')) {
    function debuglog($text)
    {
        // заглушка
    }
}

require_once(dirname(__FILE__) . '/KkmKkm.php');
require_once(dirname(__FILE__) . '/KkmOrders.php');

/**
 * Для интеграции с шаблонами
 */
class KkmAssist
{

    private $kkm = null;
    private $orders;
    static $check;

    public function __construct()
    {
        $this->kkm = new KkmKkm();
        $this->orders = new KkmOrders();
    }

    /**
     * Информация о кассе
     * @return KkmInfo
     */
    public function getKkm()
    {
        return $this->kkm->info();
    }

    /**
     * возвращает print_r($check) обрамленный pre
     * @param array|integer $id
     * @return string
     */
    public function debug_out($id)
    {
        if (is_array($id)) {
            $id = $id['id'];
        }
        $check_info = $this->orders->get_order_info($id);
        return '<pre>' . print_r($check_info, true) . '</pre>';
    }

    /**
     * Админская плашка с индикацией статуса кассы
     * @param boolean $css
     * @return string
     */
    public function kkm_status($css = true)
    {
        $css_part = '';
        if ($css) {
            $css_part = '<link href="/design/kkmserver/informer.css" rel="stylesheet" type="text/css" media="screen"/> ';

        }
        // последний сеанс , кол-во в статусе ошибка , статус
        $info = $this->getKkm();
        $why = $info->last;

        if ($this->kkm_isOnline()) {
            $status = 'on'; // онлайн
        } else {
            $status = 'off'; // офлайн
            if($info->last<Date("Y-m-d H:i:s")){
                $why = 'Нет связи '.$info->last;
            }elseif ($info->OnOff != 1){
                $why = 'Выключена';
            }elseif ($info->Active != 1){
                $why = 'Нет связи между кассой и ккмсервером';
            }elseif ($info->PaperOver == 1){
                $why = 'Закончилась бумага';
            }
        }

        // есть ошибки
        $errors = '';
        $err_count = $this->orders->count_by_kkm_fiscal(KkmOrders::STATUS_FAILED);
        if ($err_count > 0) {
            $errors = '<span><a href="/kkmserver/admin.php">' . $err_count . '</a></span>';
        }


        return <<<HTML
{$css_part}
<div id="kkm_status" class="icon {$status}" title="{$why}">{$errors}</div>
HTML;

    }

    /**
     * Касса доступна ?
     * @return boolean
     */
    public function kkm_isOnline()
    {
        return $this->kkm->isOnline();
    }

    /**
     * Посетитель с того же ip, что и запущенный ккмсервер
     * @return boolean
     */
    public function kkm_isTester()
    {
        return (boolean)$this->kkm->isTester();
    }

    /**
     * @return string
     */
    public function ip()
    {
        return kkmserver_ip();
    }

    /**
     * время последнего вызова калбака
     * @return string
     */
    public function kkm_last()
    {
        return $this->kkm->getLast();
    }

    /**
     * проверить наличие и получить данные чека для гетеров
     * @param $id
     * @return bool - есть или нет такой фискализированный чек
     */
    public function checkReceipt($id)
    {
        self::$check = $this->orders->get_order_info($id);
        if (false === self::$check) {
            return false;
        }
        if (self::$check->kkm_fiscal <> KkmOrders::STATUS_REGISTERED) {
            return false;
        }
        list($date, self::$check->time) = explode(' ', self::$check->fiscal_receipt_created);
        list($y, $m, $d) = explode('-', $date);
        self::$check->date = $d . '.' . $m . '.' . $y;
        self::$check->fiscal_receipt_created = self::$check->date . ' ' . self::$check->time;
        return true;
    }

    /**
     * Получить данные чека в шаблон для присвоения переменной
     * @return KkmCheck
     */
    public function receipt()
    {
        return self::$check;
    }

    // =================================================================
    //  Гетеры отдельных свойств чека
    // =================================================================

    /**
     * возвращает готовую картинку &lt;img src="...." /&gt;
     * @param string $level
     * @param int $size
     * @param int $margin
     * @return string
     */
    public function getQrCode($level = '', $size = 0, $margin = 0)
    {
        return '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG8AAABvAQMAAADYCwwjAAAABlBMVEX///8AAABVwtN+AAABYklEQVQ4jbWUMY7EIAxFPaJIl1wAiWvQcaXMBTLJBeBK6bgGEhcYOooo3u/Z2e0G0gxK4SfF+PuDIfraUsx5q2ZjFZjXHq6UluEYHY0SdzDUNFpanPEuX0GyxxTTeA3v+0GO/QWEKhR6ROP/RDYQ/Xor33/7n1HcC5HL/hu3UZWaHlFP2OFVqI3eqadj2H5ewJPgDPSzp4M6SFPUDzYl5tNRFxfLhfUUmatZezhbPQ/pxpAkhZqoNjjD5hwyxz6Wne67kdtlX141MTBSjtGadcg9JLLoF8ExWxHZxmnHvTIyEVKujapE+INCeqbcRa584roOzNxHb5Wn/LSZdzGnjRuL+FtFuuS2cbVyTwL0u0Q9fNo0DnL0XCW3ifT6Xy9o1r1H4zNiyrB/xvB620ecu0w6jv4ChorXTC/u7WQX0Swaue3v3CamxeHpSyPx2sOVcoiGI+I+QkyIqnC6S+Nt/Nr6AYPsnco7xY49AAAAAElFTkSuQmCC">';
    }

    /**
     * Номер заказа
     * @return int
     */
    public function getIdOrder()
    {
        return self::$check->id_order;
    }

    /**
     * Номер попытки напечатать
     * @return int
     */
    public function getFiscalTry()
    {
        return self::$check->fiscal_try;
    }

    /**
     * Статус обработки фискализации
     * @return int
     */
    public function getKkmFiscal()
    {
        return self::$check->kkm_fiscal;
    }

    /**
     * Сумма
     * @return float
     */
    public function getKkmFiscalAmount()
    {
        return self::$check->kkm_fiscal_amount;
    }

    /**
     * часть которая закодирована в qrCode
     * @return string
     */
    public function getKkmUrl()
    {
        return self::$check->kkm_url;
    }

    /**
     * текст ошибки от апи
     * @return string
     */
    public function getKkmError()
    {
        return self::$check->kkm_error;
    }

    /**
     * Номер чека
     * @return int
     */
    public function getFiscalReceiptNumber()
    {
        return self::$check->fiscal_receipt_number;
    }

    /**
     * Дата время фискализации
     * @return string
     */
    public function getFiscalReceiptCreated()
    {
        return self::$check->fiscal_receipt_created;
    }

    /**
     * Время отдельно
     * @return string
     */
    public function getTime()
    {
        return self::$check->time;
    }

    /**
     * Дата отдельно
     * @return string
     */
    public function getDate()
    {
        return self::$check->date;
    }

    /**
     * Номер смены
     * @return int
     */
    public function getShiftNumber()
    {
        return self::$check->shift_number;
    }

    /**
     * номер фискального накопителя
     * @return string
     */
    public function getFiscalStorageNumber()
    {
        return self::$check->fiscal_storage_number;
    }

    /**
     * Рег номер кассы
     * @return string
     */
    public function getRegisterRegistrationNumber()
    {
        return self::$check->register_registration_number;
    }

    /**
     * Фискальный номер документа
     * @return int
     */
    public function getFiscalDocumentNumber()
    {
        return self::$check->fiscal_document_number;
    }

    /**
     * Фискальный признак документа
     * @return int
     */
    public function getFiscalDocumentAttribute()
    {
        return self::$check->fiscal_document_attribute;
    }

    /**
     *
     * @return string
     */
    public function getIp()
    {
        return self::$check->ip;
    }

    /**
     * Кассир
     * @return string
     */
    public function getCashier()
    {
        return self::$check->cashier;
    }

    /**
     *
     * @return string
     */
    public function getSendToServer()
    {
        return self::$check->send_to_server;
    }

}