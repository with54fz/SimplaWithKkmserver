{* *****************************************************************************************
   Для покупателей (как в приложение налоговой)

   @todo: правим название офд , его ссылку
   @todo: решаете о доступности печатной формы
   @todo: и как в примечании,

   ***************************************************************************************** *}
{if $order->paid == 1 && $kkmAssist->checkReceipt($order->id)}{*когда показывать*}

<h2>Ваш чек</h2>
<p>Проверить чек <a href="https://kkt-online.nalog.ru/" target="_blank">официальным мобильным приложением
        покупателя</a>
    по QrCodу и на сайте ОФД <a target="_blank" href="http://ssylka-na-formu-proverki-vashego-ofd/">имя офд</a></p>
<style>
    .r {
        text-align: right
    }
</style>

<div style="background-color:white;width:360px;padding:16px;border:1px solid #888">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td rowspan="7">{$kkmAssist->getQrCode()}</td>
        </tr>


        <tr>
            <td>Дата: {$kkmAssist->getDate()}</td>
            <td class="r">Время: {$kkmAssist->getTime()}</td>
        </tr>
        <tr>
            <td>Вид чека:</td>
            <td class="r">приход</td>
        </tr>
        <tr>
            <td>ИТОГО:</td>
            <td class="r">&#8801;{$kkmAssist->getKkmFiscalAmount()}</td>
        </tr>
        <tr>
            <td>ФН №:</td>
            <td class="r">{$kkmAssist->getFiscalStorageNumber()}</td>
        </tr>
        <tr>
            <td>ФД №:</td>
            <td class="r">{$kkmAssist->getFiscalReceiptNumber()}</td>
        </tr>
        <tr>
            <td>ФПД:</td>
            <td class="r">{$kkmAssist->getFiscalDocumentAttribute()}</td>
        </tr>
    </table>
</div>
<p><i>Поправьте форму выше (порядок и названия полей) как в форме<br/>вашего офд. На образце выше дан вариант для
        ручного ввода<br/> в приложении налоговой.</i>

    {if $smarty.session.admin == 'admin'}
        <p>
        <a href="/check/{$order->url}/" target="_blank">Печатный образ чека</a>
        показывается только админу (или убрать
        проверки  здесь и в шаблоне чека)
        </p>
    {/if}


{/if}{* закрыли когда блок виден*}

