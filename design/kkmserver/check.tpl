<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="NOINDEX,NOFOLLOW"/>
</head>
<body>
{if $smarty.session.admin <> 'admin'}
 <h1>Доступ ограничен</h1>
{else}
{if $order->paid == 1}
    {if $kkmAssist->checkReceipt($order->id)}
        {assign var="receipt" value=$kkmAssist->receipt()}
        {assign var="kkm" value=$kkmAssist->getKkm()}
        <style>
            .r {
                text-align: right
            }
        </style>
        <div style="background-color:white;width:360px;padding:16px;border:1px solid #888">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="2">
                        <img src="/design/{$settings->theme|escape}/images/logo.png" title="Великолепный интернет-магазин"
                             alt="Великолепный интернет-магазин">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"><h3>{$kkm->NameOrganization}</h3></td>
                </tr>
                <tr>
                    <td colspan="2">{$kkm->AddressSettle}, {$kkm->PlaceSettle}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"><br/>КАССОВЫЙ ЧЕК № {$receipt->fiscal_receipt_number}
                        (ПРИХОД)<br><br></td>
                </tr>
                <tr>
                    <td>ИНН организации:</td>
                    <td class="r">{$kkm->InnOrganization}</td>
                </tr>
                <tr>
                    <td>Заводской номер ККТ:</td>
                    <td class="r">{$kkm->KktNumber}</td>
                </tr>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Кассир:</td>
                    <td class="r">{$receipt->cashier}</td>
                </tr>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Смена: {$receipt->shift_number}</td>
                    <td class="r">{$receipt->fiscal_receipt_created}</td>
                </tr>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="border-bottom:2px solid #000">Клиент:</td>
                    <td class="r" style="border-bottom:2px solid #000">{$order->email|escape}</td>
                </tr>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                {foreach $purchases as $purchase}
                    <tr>
                        <td colspan="2">{$purchase->product_name|escape} {$purchase->variant_name|escape}</td>
                    </tr>
                    <tr>
                        <td>Без НДС: 0</td>
                        <td class="r">{$purchase->amount}&nbsp;{$settings->units}
                            &times; {($purchase->price)|convert}&nbsp;{$currency->sign}</td>
                    </tr>
                {/foreach}
            </table>
            <br/>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Применяя СН:</td>
                    <td class="r">УСН (Доход-расход)</td>
                </tr>
                <tr>
                    <td style="border-top:2px solid #000">Дата: {$receipt->date}</td>
                    <td style="border-top:2px solid #000" class="r">Время: {$receipt->time}</td>
                </tr>
                <tr>
                    <td>Вид операции:</td>
                    <td class="r">приход</td>
                </tr>
                <tr>
                    <td>ИТОГ:</td>
                    <td class="r">&#8801;{$receipt->kkm_fiscal_amount}</td>
                </tr>
                <tr>
                    <td>ФН №:</td>
                    <td class="r">{$receipt->fiscal_storage_number}</td>
                </tr>
                <tr>
                    <td>ФД №:</td>
                    <td class="r">{$receipt->fiscal_receipt_number}</td>
                </tr>
                <tr>
                    <td>ФПД:</td>
                    <td class="r">{$receipt->fiscal_document_attribute}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">{$kkmAssist->getQrCode()}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">
<b>Данная форма не содержит всех обязательных признаков кассового чека.</b>
                    </td>
                </tr>
            </table>
        </div>
                        <br />
                            Это только демонстрация возможностей класса KkmAssist,<br />
                            посредника между шаблонами сайта и данными модуля.<br />
			    Вместе с модулем поставляется полный вариант.<br />
			    За  дополнительную плату возможна доработка под ваш шаблон.<br />
			    Также на этой странице можно разместить условия гарантии<br />
			     и другую информацию.			
    {/if}
{/if}
{/if}
</body>
</html>