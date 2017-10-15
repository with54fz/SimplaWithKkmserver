<?php
require_once(dirname(__FILE__) . '/../../api/Orders.php');

if (!function_exists('kkmserver_ip')) {
    function kkmserver_ip()
    {
        // не правьте здесь , определите ее в kkmserver/config.php
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Сущность из таблицы
 */
class KkmCheck
{
    /**
     * @var integer
     */
    public $id_order = 0;
    /**
     * @var integer
     */
    public $fiscal_try = 0;
    /**
     * @var integer
     */
    public $kkm_fiscal = 0;
    /**
     * @var  float
     */
    public $kkm_fiscal_amount = 0;// decimal(10,2)  NOT NULL,
    /**
     * @var string
     */
    public $kkm_url = '';// varchar(250) NOT NULL,
    /**
     * @var string
     */
    public $kkm_error = '';// varchar(250) NOT NULL,
    /**
     * @var integer
     */
    public $fiscal_receipt_number = 0;// int(11) NOT NULL,
    /**
     * @var string
     */
    public $fiscal_receipt_created = '0000-00-00 00:00:00';// datetime NOT NULL,
    /**
     * @var integer
     */
    public $shift_number = 0;// int(11) NOT NULL,
    /**
     * @var string
     */
    public $fiscal_storage_number = '';// varchar(64) NOT NULL,
    /**
     * @var string
     */
    public $register_registration_number ='';// varchar(64) NOT NULL,
    /**
     * @var integer
     */
    public $fiscal_document_number =0;// int(11) NOT NULL,
    /**
     * @var integer
     */
    public $fiscal_document_attribute =0 ;// int(11) NOT NULL,
    /**
     * @var string
     */
    public  $ip = ''; // varchar(40) NOT NULL,

    /**
     * @var string
     */
    public $cashier = ''; // varchar(100) NOT NULL,

    /*
     * @var string
     */
    public $payment_date = '';// datetime NOT NULL,

    /**
     * @var string
     */
    public $payment_details = '';// TEXT,

    /**
     * @var string
     */
    public $send_to_server = '';// datetime NOT NULL,
}

/**
 * Class KkmOrders
 * демо заглушка
 *
 * @property Database $db
 */
class KkmOrders extends Orders
{
    const STATUS_NEW = 0;  // можно посылать на фискализатор
    const STATUS_SEND = 1; // данные уже посланы
    const STATUS_REGISTERED = 2; // чек за фискализирован
    const STATUS_FAILED = 3; // произошла ошибка
    const STATUS_MANUAL = 4; // проблема со сбоем разрешена в ручную

    /**
     * @var string Для протоколирования с какого адреса
     */
    private $ip = '';

    public function __construct()
    {
        $this->ip = kkmserver_ip();
    }

    /**
     * Статистика по статусам фискализации
     * @param int $status должен быть больше 0
     * @return bool|int
     */
    public function count_by_kkm_fiscal($status)
    {
        return 123;
    }

    /**
     * @param $id
     * @return bool|KkmCheck
     */
    public function get_order_info($id)
    {
       $info = new KkmCheck();
       if(!isset($_SESSION['admin'])  || $_SESSION['admin'] !== 'admin'){
         return $info; // пустышка клиентам
       }

       // админу тестовые данные
        $info->id_order = $id;
        $info->fiscal_try = 0;
        $info->kkm_fiscal = self::STATUS_REGISTERED; // see STATUS_xxxx
        $info->kkm_fiscal_amount = '99999.99';
        // qrCode заглушен отдельно , правка урла на нем не сказывается.
        $info->kkm_url = 't=20171015T100451&s=20300.00&fn=0149060506089651&i=1&fp=2023228047&n=1';
        $info->kkm_error = 'Текст ошибки, показать клиенту. Обычно из него понятно что делать.';
        $info->fiscal_receipt_number = 123;
        $info->fiscal_receipt_created = '2017-10-15 08:06:31';
        $info->shift_number = 12;
        $info->fiscal_storage_number = '0149060506089651';
        $info->register_registration_number ='вписать в конфиге';
        $info->fiscal_document_number = 1;
        $info->fiscal_document_attribute = '2023228047' ;
        $info->ip = '127.0.0.1';
        $info->cashier = 'Кассир И.О.';
        $info->payment_date = '2017-10-15 08:06:02';
        $info->payment_details = 'тестовые данные';
        $info->send_to_server = '2017-10-15 08:06:30';

        return $info;
    }

}