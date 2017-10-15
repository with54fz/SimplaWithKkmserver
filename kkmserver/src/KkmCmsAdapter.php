<?php

/**
 * класс Simpla пришлось переопределить, так как нет возможности перекрытия классов своими
 *
 * @property Config $config
 * @property Request $request
 * @property Database $db
 * @property Settings $settings
 * @property Products $products
 * @property Variants $variants
 * @property Categories $categories
 * @property Brands $brands
 * @property Features $features
 * @property Money $money
 * @property Pages $pages
 * @property Blog $blog
 * @property Cart $cart
 * @property Image $image
 * @property Delivery $delivery
 * @property Payment $payment
 * @property KkmOrders $orders  - перекрытый мной класс
 * @property Users $users
 * @property Coupons $coupons
 * @property Comments $comments
 * @property Feedbacks $feedbacks
 * @property Notify $notify
 * @property Managers $managers
 */
class KkmCmsAdapter
{
    // Свойства - Классы API
    private $classes = array(
        'config'     => 'Config',
        'request'    => 'Request',
        'db'         => 'Database',
        'settings'   => 'Settings',
        'design'     => 'Design',
        'products'   => 'Products',
        'variants'   => 'Variants',
        'categories' => 'Categories',
        'brands'     => 'Brands',
        'features'   => 'Features',
        'money'      => 'Money',
        'pages'      => 'Pages',
        'blog'       => 'Blog',
        'cart'       => 'Cart',
        'image'      => 'Image',
        'delivery'   => 'Delivery',
        'payment'    => 'Payment',
        'orders'     => 'Orders',
        'users'      => 'Users',
        'coupons'    => 'Coupons',
        'comments'   => 'Comments',
        'feedbacks'  => 'Feedbacks',
        'notify'     => 'Notify',
        'managers'   => 'Managers'
    );

    // Созданные объекты
    private static $objects = array();

    /**
     * Конструктор оставим пустым, но определим его на случай обращения parent::__construct() в классах API
     */
    public function __construct()
    {
        // здесь проинитим свой класс заказов так как у него другой путь
        include_once(dirname(__FILE__).'/KkmOrders.php');
        self::$objects['orders'] = new KkmOrders();
    }

    /**
     * Магический метод, создает нужный объект API
     * @param string
     * @return mixed
     */
    public function __get($name)
    {
        // Если такой объект уже существует, возвращаем его
        if(isset(self::$objects[$name]))
        {
            return(self::$objects[$name]);
        }

        // Если запрошенного API не существует - ошибка
        if(!array_key_exists($name, $this->classes))
        {
            return null;
        }

        // Определяем имя нужного класса
        $class = $this->classes[$name];

        // !!!! поправлем путь
        include_once(dirname(__FILE__).'/../../api/'.$class.'.php');

        // Сохраняем для будущих обращений к нему
        self::$objects[$name] = new $class();

        // Возвращаем созданный объект
        return self::$objects[$name];
    }

}