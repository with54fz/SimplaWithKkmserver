<?php
/**
 * (c) http://with54fz.ru/ , email: main@with54fz.ru
 */
require_once(dirname(__FILE__) . '/KkmCmsAdapter.php');


/**
 * состояние Ккт
 * @property  string $UrlServerOfd  - URL или IP сервера ОФД
 * @property  number $PortServerOfd - IP-порт сервера ОФД
 * @property  string $NameOFD - Наименование ОФД
 * @property  string $UrlOfd  - префикс URL ОФД для поиска чека
 * @property  string $InnOfd - ИНН ОФД
 * @property  string $InnOrganization - ИНН организации
 * @property  string $NameOrganization - Наименование организации
 * @property  string $AddressSettle - Адрес установки ККМ
 * @property  string $PlaceSettle - Место установки ККМ
 * @property  string $TaxVariant - При нескольких СНО через запятую, например: "0,3,5"
 * @property  string $KktNumber - Заводской номер кассового аппарата
 * @property  string $FnNumber - Заводской номер фискального регистратора
 * @property  string $RegNumber - Регистрационный номер ККТ (из налоговой)
 * @property  boolean $OnOff - выключена-включенна
 * @property  boolean $Active - автивна/неактивна (на связи)
 * @property  boolean $FN_IsFiscal - Кассовый принтер или фискальный аппарат
 * @property  boolean $FN_MemOverflowl - Приближается переполнение фискального накопителя
 * @property  string $FN_DateEnd - Когда нужно менять фискальный накопитель
 * @property  string $OFD_Error  - Если не пусто, то сообщение об ошибке обмена с ОФД
 * @property  number $OFD_NumErrorDoc -  Количество не переданных документов в ОФД
 * @property  string $OFD_DateErrorDoc - Дата первого не переданного документа в ОФД
 * @property  number $SessionState - Статус сессии 1-Закрыта, 2-Открыта, 3-Открыта, но закончилась (3 статус на старых ККМ может быть не опознан)
 * @property  string $FFDVersion
 * @property  string $FFDVersionFN
 * @property  string $FFDVersionKKT
 * @property  boolean $PaperOver -Закончилась бумага
 * @property  number $BalanceCash - Остаток наличных
 * @property  string $LessType1 - Название 1 типа безналичных расчетов
 * @property  string $LessType2 - Название 2 типа безналичных расчетов
 * @property  string $LessType3 - Название 3 типа безналичных расчетов
 * @property  string $last - дата-время последнего вызова callback
 */
class KkmInfo{}

/**
 * Модель информации о состоянии кассы
 * демо заглушка
 */
class KkmKkm extends KkmCmsAdapter
{

    /**
     * KkmKkm constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ip = kkmserver_ip();
    }


    /**
     *  у кого в этот час запущен ккм сервер ?
     *  добавлен для демосайта simplacms54fz.ru
     * @return bool|int
     */
    public function isTester()
    {
        return true;
    }

    /**
     * Касса на связи ?
     * @return bool
     */
    public function isOnline($need_answer = true)
    {
        return $need_answer;
    }

    /**
     * Для отладки время последнего вызова калбака
     * @return string
     */
    public function getLast()
    {
        return Date('Y-m-d H:i:s');
    }

    /**
     * @return KkmInfo
     */
    public function info()
    {
	$info = new KkmInfo();
	$info->UrlServerOfd = "URL или IP сервера ОФД";
	$info->PortServerOfd = "IP-порт сервера ОФД";
	$info->NameOFD = "Наименование ОФД";
	$info->UrlOfd = "префикс URL ОФД для поиска чека";
	$info->InnOfd = "ИНН ОФД";
	$info->InnOrganization = "ИНН организации";
	$info->NameOrganization = "Наименование организации";
	$info->AddressSettle = "Адрес установки ККМ";
	$info->PlaceSettle = "Место установки ККМ";
	$info->TaxVariant = "При нескольких СНО через запятую, например: 0,3,5";
	$info->KktNumber = "ЗН кассы";
	$info->FnNumber = "ЗН фиск.рег-ра";
	$info->RegNumber = "РегНомерККТ";
	$info->OnOff = 1; // "выключена-включенна"
	$info->Active = 1; // "аквтивна/неактивна (на связи)"
	$info->FN_IsFiscal = 1; // "Кассовый принтер или фискальный аппарат"
	$info->FN_MemOverflowl = 0 ; // "Приближается переполнение фискального накопителя";
	$info->FN_DateEnd = "Когда нужно менять фискальный накопитель";
	$info->OFD_Error = "Если не пусто, то сообщение об ошибке обмена с ОФД";
	$info->OFD_NumErrorDoc = "Количество не переданных документов в ОФД";
	$info->OFD_DateErrorDoc = "Дата первого не переданного документа в ОФД";
	$info->SessionState = 2; // "Статус сессии 1-Закрыта, 2-Открыта, 3-Открыта, но закончилась (3 статус на старых ККМ может быть не опознан)"
	$info->FFDVersion  = "";
	$info->FFDVersionFN   = "";
	$info->FFDVersionKKT  = "";
	$info->PaperOver  =  0 ; // "Закончилась бумага"
	$info->BalanceCash = "Остаток наличных";
	$info->LessType1 = "Название 1 типа безналичных расчетов";
	$info->LessType2 = "Название 2 типа безналичных расчетов";
	$info->LessType3 = "Название 3 типа безналичных расчетов";
	$info->last = Date('Y-m-d H:i:s'); // дата-время последнего вызова callback

	return $info;
    }
}
