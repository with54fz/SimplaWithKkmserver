<?php

require_once('api/Simpla.php');

/**
 * Class TestMoney
 * @property Orders $orders
 */
class TestMoney extends Simpla
{	
	public function checkout_form($order_id, $button_text = null)
	{
		return <<<HTML
<br />
<p><b>Проверять ли статус кассы в момент приходы оплаты ?</b><br>
На этот вопрос нельзя однозначно ответить. Подробнее размещу отдельную статью!</p>		
<br />
<p>		
<a class='checkout_button' href="/payment/TestMoney/callback.php?id={$order_id}">Оплатить</a>
<a class='checkout_button' href="/payment/TestMoney/callback.php?id={$order_id}&force=1">Все равно оплатить</a>
</p>
<br />
<p><b>Работать будет так и так.</b><br /> 
Если без проверки, то модуль проинформирует, что нужно как можно быстрее востановить связь.</p>
<p>
А ограничивать же выбор способа оплаты и перехода на сайт платежной системы нет разумных причин, так как процес 
оплаты занимает от нескольких минут до нескольких часов (например через киоски оплат).		
</p>

HTML;
		
	}

}