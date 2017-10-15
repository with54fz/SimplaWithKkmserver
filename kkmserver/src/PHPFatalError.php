<?php
if (function_exists("xdebug_disable")) {
    xdebug_disable();
}

/**
 * Class PHPFatalError
 *
 * ЭТИ НАСТРОЙКИ ДЕЛАЮТСЯ В CALLBACK.PHP (п.2)
 * define('ERROR_EMAIL', 'support@simplacms54fz.ru'); // кому
 * define('EMAIL_FROM', 'kkm@simplacms54fz.ru'); // от кого
 * define('EMAIL_TRANSPORT', 'TELEGRAM'); // SMTP or MAIL or TELEGRAM
 * // если smtp
 * define('SMTP_HOST','mail.simplacms54fz.ru');
 * define('SMTP_PORT',465);
 * define('SMTP_SSL',true);
 * define('SMTP_USER','kkm@simplacms54fz.ru'); // учетка для from
 * define('SMTP_PASSWORD','*******');
 * define('SMTP_DEBUG',0) ;
 * // telegram
 * define('BOT_TOOKEN','9999999:******************************');
 * define('BOT_CHANNEL','999999999999');
 */
class PHPFatalError
{
    /**
     *  Назначаем как обработчик
     */
    public static function setHandler()
    {
        register_shutdown_function(array('PHPFatalError', 'handleShutdown'));
        set_error_handler(array('PHPFatalError', 'error_handler'));
    }


    /**
     * callback set_error_handler
     * @param int $errno
     * @param null $errstr
     * @param null $errfile
     * @param null $errline
     * @return bool
     */
    public static function error_handler($errno = 0, $errstr = null, $errfile = null, $errline = null)
    {
        if ($errno > 0) {
            if (is_null($errline)) {
                $errline = -100;
            }

            $level = self::errorToString($errno);

            $message = "На сайте произошла ошибка. Информация для php программиста.\r\n\r\n";
            $message .= 'Тип ошибки:' . $level . "\r\n";
            $message .= $errstr . "\r\n";
            $message .= 'Файл: ' . $errfile . "\r\n";
            $message .= 'Cтрока: ' . $errline . "\r\n";
            $message .= 'Время: ' . Date("Y-m-d H:i:s") . "\r\n";
            if (EMAIL_TRANSPORT !== 'TELEGRAM') {
                $message .= "------------------  [source code] -----------------------------\r\n";
                $fc = file($errfile);
                $s = $errline - 15;
                $end = $errline + 15;
                if ($s < 0) $s = 0;
                for ($i = $s; $i <= $end; $i++) {
                    $message .= ($i + 1) . ': ' . $fc[$i];
                }
                $message .= "------------------  [/source code]  ---------------------------\r\n";
                $message .= "\r\n\r\n";
            }
            self::email($message, $level . ' on ' . $_SERVER['HTTP_HOST']);

            return true;
        }

        return false;
    }

    /**
     * @param integer $value
     * @return string
     */
    public static function errorToString($value)
    {
        $level_names = array(
            E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE', E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR', E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR', E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR', E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE'
        );

        if (defined('E_STRICT')) {
            $level_names[E_STRICT] = 'E_STRICT';
        }
        $levels = array();
        if (($value & E_ALL) == E_ALL) {
            $levels[] = 'E_ALL';
            $value &= ~E_ALL;
        }

        foreach ($level_names as $level => $name) {
            if (($value & $level) == $level) {
                $levels[] = $name;
            }
        }
        return implode(' | ', $levels);
    }

    /**
     * Разбор фатальных ошибок
     */
    public static function handleShutdown()
    {
        if (($error = error_get_last())) {
            $buffer = ob_get_contents();
            ob_clean();
            if (preg_match("#(Fatal|Parse)\serror#", $buffer, $regs)) {
                $level = self::errorToString($error['type']);

                $message = "На сайте произошла ошибка. Информация для php программиста.\r\n\r\n";
                $message .= 'Тип ошибки:' . $level . "\r\n";
                $message .= $error['message'] . "\r\n";
                $message .= 'Файл: ' . $error['file'] . "\r\n";
                $message .= 'Cтрока: ' . $error['line'] . "\r\n";
                $message .= 'Время: ' . Date("Y-m-d H:i:s") . "\r\n";
                if (EMAIL_TRANSPORT !== 'TELEGRAM') {
                    $message .= "------------------  [source code] -----------------------------\r\n";
                    $fc = file($error['file']);
                    $s = $error['line'] - 15;
                    $end = $error['line'] + 15;
                    if ($s < 0) $s = 0;
                    for ($i = $s; $i <= $end; $i++) {
                        $message .= ($i + 1) . ': ' . $fc[$i];
                    }
                    $message .= "------------------  [/source code]  ---------------------------\r\n";
                    $message .= "\r\n\r\n" . 'Перехваченный вывод на экран : ' . "\r\n";
                    $message .= $buffer;
                }
                self::email($message, $regs[0] . ' on ' . $_SERVER['HTTP_HOST']);
                echo 'Fatal end. Check you email.';
            }
        }
    }


    /**
     * Единая функция вывода вывода SMTP / TELEGRAM / MAIL
     * @param string $message
     * @param null|string  $subject
     */
    public static function email($message, $subject = null)
    {
        if (is_null($subject)) {
            $subject = 'Уведомление от кассы';
        }

        $subject_tg = $subject;
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

        if (EMAIL_TRANSPORT == 'SMTP') {
		// в демо отсутвует
        } elseif (EMAIL_TRANSPORT == 'TELEGRAM') {
		// в демо отсутвует
        } else { // MAIL
		// в демо отсутвует
        }

	echo '<h1>'.$subject_tg.'</h1>';
	echo '<pre>';
	echo $message;
	echo '</pre>';
    }
}


