{if $order->paid == 1}
    {* *****************************************************************************************
                          Для админа
       ***************************************************************************************** *}
    {if $smarty.session.admin == 'admin'}
        <div id="kkmadmin">
            <h1>Администратор чеков</h1>
            {if $order->paid == 1}
                {if $kkmAssist->checkReceipt($order->id)}{/if}
                {* ожидает *}
                {if $kkmAssist->getKkmFiscal()==KkmOrders::STATUS_NEW}
                    <b>Ожидается вызов от kkmserver для посылки данных на ККМ</b>
                    <br/>
                    предыдущий сеанс связи был в {$kkmAssist->kkm_last()}
                    <br/>
                    <br/>
                    <br/>
                    Вы получили уведомление на мобильный телефон об оплате заказа, но в офисе пропал интернет.
                    <br/>
                    И Вы
                    <b>не можете востановить оперативно связь</b>
                    между офисом и сайтом,
                    <br/>
                    а
                    <b>данные заказа,есть</b>
                    например в 1с, то чтобы не задвоило можно пометить его через смартфон.
                    <br/>
                    <br/>
                    <a class="btn red" href="/kkmserver/admin.php?id={$order->id}&task=solve">Напечатаю из 1С</a>
                {/if}
                {* послан *}
                {if $kkmAssist->getKkmFiscal()==KkmOrders::STATUS_SEND}
                    <b>Ожидается ответ от kkmserver с фискальными признаками</b>
                    <br/>
                    передан в {$kkmAssist->getSendToServer()}
                    <br/>
                    предыдущий сеанс связи был в {$kkmAssist->kkm_last()}
                    <br/>
                    <br/>
                    Даже после обрыва связи модуль автоматически попытается получить информацию.
                {/if}
                {* фискализирован *}
                {if $kkmAssist->getKkmFiscal()==KkmOrders::STATUS_REGISTERED}
                    <b>с чеком все ок</b>
                    <a class="btn green" href="/check/{$order->url}/" target="_blank">Печать</a>
                    <br/>
                    <br/>
                    в следующей версии возможно здесь будет возможность сделать чек возврата
                {/if}
                {* ошибка *}
                {if $kkmAssist->getKkmFiscal()==KkmOrders::STATUS_FAILED}
                    <h2 style="color:red">ошибка</h2>
                    <b>{$kkmAssist->getKkmError()}</b>
                    <br/>
                    попыток фискализации : {$kkmAssist->getFiscalTry()}
                    <br/>
                    <br/>
                    <a class="btn green" href="/kkmserver/admin.php?id={$order->id}&task=retry">Еще раз</a>
                    <a class="btn red" href="/kkmserver/admin.php?id={$order->id}&task=solve">Решена</a>
                {/if}
                {* в ручную *}
                {if $kkmAssist->getKkmFiscal()==KkmOrders::STATUS_MANUAL}
                    <b style="color:red">ручная обработка чека</b>
                {/if}
            {/if}
        </div>
    {/if}
{/if}