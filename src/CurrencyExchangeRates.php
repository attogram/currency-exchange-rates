<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;

class CurrencyExchangeRates
{
    /** @var string Version*/
    const VERSION = '0.0.6-alpha';

    /** @var Router */
    private $router;

    public function route()
    {
        $this->router = new Router();
        $this->router->setForceSlash(true);
        $this->router->allow('/', 'home');
        $this->router->allow('/about/', 'about');
        $this->router->allow('/admin/', 'admin');
        $this->router->allow('/admin/get/?', 'adminGet');
        $this->router->allow('/?/', 'currency');
        $this->router->allow('/?/?/', 'currencyPair');
        $match = $this->router->match();
        if ($match && method_exists($this, $match)) {
            $this->{$match}();

            return;
        }
        $this->error404();
    }

    /**
     * @param string $message
     */
    private function error404(string $message = 'Page Not Found')
    {
        header('HTTP/1.0 404 Not Found');
        print '<pre>

        404 ' . $message . '


        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        </pre>';
    }

    private function home()
    {
        print '<pre>

        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        
        <a href="CHF/">CHF</a>    <a href="CHF/USD/">CHF/USD</a>
        
        <a href="EUR/">EUR</a>    <a href="EUR/USD/">EUR/USD</a>
        
        <a href="ILS/">ILS</a>    <a href="ILS/USD/">ILS/USD</a>
        
        <a href="RUB/">RUB</a>    <a href="RUB/USD/">RUB/USD</a>


        <a href="about/">about</a>
        
        
        
        <a href="admin/">admin</a>

        </pre>';
    }

    private function about()
    {
        print '<pre>

        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>
        
        ';
        foreach(Currency::$currencies as $code => $currency) {
            print "\n$code\n";
            print_r($currency);
        }
        foreach(Source::$sources as $code => $source) {
            print "\n$code\n";
            print_r($source);
        }
        print '</pre>';
    }

    private function admin()
    {
        print '<pre>

        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>
        
        
        <a href="get/snb/">get ' . Source::$sources['snb']['name'] . '</a>
        
        <a href="get/ecb/">get ' . Source::$sources['ecb']['name'] . '</a>
        
        <a href="get/boi/">get ' . Source::$sources['boi']['name'] . '</a>
        
        <a href="get/cbr/">get ' . Source::$sources['cbr']['name'] . '</a>

        </pre>';
    }

    private function adminGet()
    {
        $source = $this->router->getVar(0);
        if (!Source::isValidSourceCode($source)) {
            $this->error404('Currency Source Not Found');

            return;
        }
        print "ADMINGET $source";
    }

    private function currency()
    {
        $currency = $this->router->getVar(0);
        if (!Currency::isValidCurrencyCode($currency)) {
            $this->error404();

            return;
        }
        print "CURRENCY $currency";
    }

    private function currencyPair()
    {
        $source = $this->router->getVar(0);
        $target = $this->router->getVar(1);
        if (!Currency::isValidCurrencyCode($source) || !Currency::isValidCurrencyCode($target)) {
            $this->error404();

            return;
        }
        print "CURRENCYPAIR $source $target";
    }
}
