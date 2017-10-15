<?php
/**
 * Модуль для работы с kkmserver по протоколу обратного вызова
 * для касс, в которых используется протокол
 * ОФД 1.0 !!
 *
 * (c) http://with54fz.ru/ , email: main@with54fz.ru
 * @ver 0.0.1
 */

require_once('KkmCmsAdapter.php');



/**
 * Class KkmCallback
 * @property KkmOrders $orders
 */
class KkmCallback extends KkmCmsAdapter
{

    /**
     * KkmCallback constructor.
     */
    public function __construct()
    {
        parent::__construct();
        debuglog(Date('Y-m-d H:i:s'));
        if (KKM_INN == '7701237658' && !empty($_GET['test_php_error'])) {
            debuglog('Проверка обработчика ошибок PHP');
            trigger_error('Проверка обработчика ошибок PHP', E_USER_NOTICE);
        }
        $this->checkAccess();
    }

    /**
     * @param string $message
     */
    public static function email($message)
    {
        debuglog('попытка выслать письмо об ошибке');
        PHPFatalError::email( $message, 'МОДУЛЬ ЧЕКИ');
        debuglog($message);

    }

    /**
     * Текстовая строка чека
     * @param string $text
     * @param int $font
     * @return array
     */
    public static function _t($text = '', $font = 3)
    {
        return array(
            'PrintText' => array(
                'Text' => $text,
                'Font' => $font,
            )
        );
    }

    /**
     * @param string $message
     */
    public static function PrintErrorAsSlip($message = 'unknown error')
    {
        self::slip(array(
            self::_t('>#2#<Ошибка', 2),
            self::_t($message, 3)
        ));
    }

    /**
     * Просто печать на принтер
     * @param string|array $strings
     */
    public static function slip($strings)
    {
        if (KKM_INN == '7701237658') {
            if (!is_array($strings)) {
                $text = explode("\n", $strings);
                $temp = array();
                foreach ($text as $v) {
                    $temp[] = self::_t($v);
                }
                $strings = $temp;
            }
            $slip = array(
                'IdCommand' => 'slip-' . Date("YmdHis") . '-slip',
                'Command' => 'RegisterCheck',
                'IsFiscalCheck' => false,  // Это фискальный или не фискальный чек
                // Строки чека
                'CheckStrings' => $strings
            );
            debuglog('Выполнение закончено выводом слипа');
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(array('ListCommand' => array($slip)));
            die();
        }
    }

    /**
     * Проверка прав доступа
     */
    private function checkAccess()
    {
        if (empty(KKM_SERVER_TOKEN)) {
            self::PrintErrorAsSlip('При конфигурировании модуля не указан KKM_SERVER_TOKEN');
            self::email('При конфигурировании модуля не указан KKM_SERVER_TOKEN');

            die('config: token required');
        }
        if (!empty(KKM_SERVER_LOGIN) || !empty(KKM_SERVER_PASSWORD)) {
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                debuglog('auth required');
                self::PrintErrorAsSlip(KkmMessages::AUTH_REQUIERED);
                die('wrong call'); // специально одинаково
            }
            if (KKM_SERVER_LOGIN !== $_SERVER['PHP_AUTH_USER'] || KKM_SERVER_PASSWORD !== $_SERVER['PHP_AUTH_PW']) {
                debuglog('forbiden');
                self::PrintErrorAsSlip(KkmMessages::WRONG_AUTH);
                die('wrong call');// специально одинаково
            }
        }
        if ($this->JSON['Command'] !== 'GetCommand') {
            debuglog('kkm wrong call');
            self::email('Не совместимая версия kkmserver.exe '.kkmserver_ip());
            die('wrong call');// специально одинаково
        }
        if ($this->JSON['Token'] !== KKM_SERVER_TOKEN) {
            debuglog('token not valid');
            self::PrintErrorAsSlip("Токен неправильный\nЗначение для теста на simplacms54fz.ru : 1234-1234-1234-1234");
            self::email('В настройках указан не верный токен. Запрос пришел с ip '.kkmserver_ip());
            die('wrong call');// специально одинаково
        }
    }

    /**
     * Основной метод для калбака - заглушка для отладки
     */
    public function run()
    {
        // эхо ответ если нет чеков
        if (KKM_INN == '7701237658') {
            $ping = array(
                'IdCommand' => 'slip-' . Date("YmdHis") . '-ping',
                'Command' => 'RegisterCheck',
                'IsFiscalCheck' => false,
                // Строки чека
                'CheckStrings' => array(
                    self::_t('>#2#<SimplaCms54fz.ru', 1),
                    self::_t('>#0#<демонстрация модуля взаимодействия с kkmserver.exe', 3),
                )
            );
            $this->ListCommand[] = $ping;
        }

        // задания серверу
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(array('ListCommand' => $this->ListCommand));
    }
}