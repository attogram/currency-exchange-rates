<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;

/**
 * Class CurrencyExchangeRates
 * @package Attogram\Currency
 */
class CurrencyExchangeRates
{
    /** @var string Version*/
    const VERSION = '0.0.1-alpha.1';

    /** @var Router */
    protected $router;

    public function route()
    {
        $this->router = new Router();
        $this->router->setForceSlash(true);
        $this->router->allow('/', 'home');
        $this->router->allow('/index.php', 'home');
        $this->router->allow('/about/', 'about');
        $this->router->allow('/admin/', 'admin');
        $this->router->allow('/?/', 'currency');
        $this->router->allow('/?/?/', 'currencyPair');
        $match = $this->router->match();
        if (!$match) {
            $this->error404();
        }
        if (!method_exists($this, $match)) {
            $this->error404('Method Not Found');
        }
        $this->{$match}();
    }

    /**
     * @param string $message
     */
    protected function error404(string $message = 'Page Not Found')
    {
        print '<h1>404 ' . $message . '</h1>';
        exit;
    }

    protected function home()
    {
        print '<HR>HOME<HR>';
    }

    protected function about()
    {
        print '<pre>ABOUT<br>';
        foreach(Source::$sources as $source) {
            print_r($source);
        }
    }

    protected function admin()
    {
        print '<HR>ADMIN<HR>';
    }

    protected function currency()
    {
        $currency = $this->router->getVar(0);
        if (!Currency::isValidCurrencyCode($currency)) {
            $this->error404('Currency Code Not Found');
        }
        print "CURRENCY = $currency";
    }

    protected function currencyPair()
    {
        $source = $this->router->getVar(0);
        if (!Currency::isValidCurrencyCode($source)) {
            $this->error404('Source Currency Code Not Found');
        }
        $target = $this->router->getVar(1);
        if (!Currency::isValidCurrencyCode($target)) {
            $this->error404('Target Currency Code Not Found');
        }
        print "CURRENCY PAIR - source = $source - target = $target";
    }
}