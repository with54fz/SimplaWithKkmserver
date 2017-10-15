{if $order->paid == 1}
{* *****************************************************************************************
                      Для админа стиль по умолчанию
   ***************************************************************************************** *}
<style>
    #kkmadmin {
        margin-top: 32px;
        background-color: lightyellow;
        padding: 16px;
        border: 1px solid #ddd
    }

    #kkmadmin h1 {
        font-size: 18px;
        border-bottom: 1px solid #ddd;
        margin: -16px -16px 16px -16px;
        padding: 8px 16px;
        background-color: yellow;
    }

    #kkmadmin .btn {
        display: inline-block;
        padding: 8px 16px;
        margin-right: 8px;
        color: white;
        text-decoration: none
    }

    #kkmadmin .green {
        background-color: green
    }

    #kkmadmin .red {
        background-color: red
    }
</style>
{/if}