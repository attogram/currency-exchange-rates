<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;
use function header;
use function method_exists;
use function print_r;

class CurrencyExchangeRates
{
    /** @var string Version*/
    const VERSION = '0.0.7-alpha';

    /** @var Database */
    private $db;

    /** @var Router */
    private $router;

    public function route()
    {
        $this->router = new Router();
        $this->router->setForceSlash(true);
        $this->router->allow('/', 'home');
        $this->router->allow('/?/', 'currency');
        $this->router->allow('/?/?/', 'currencyPair');
        $this->router->allow('/about/', 'about');
        $this->router->allow('/admin/', 'admin');
        $this->router->allow('/admin/database/', 'adminDatabase');
        $this->router->allow('/admin/get/?/', 'adminGet');

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


        <a href="database/">Database</a>
        
        </pre>';
    }

    private function adminDatabase()
    {
        print '<pre>

        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>
        
        Admin: Database:
        
        <a href="./?create=1">Create Database</a>
        
        </pre>';

        if (!$this->router->getGet('create')) {
            return;
        }

        print '<pre>   Initialize Database: ';
        $this->db = new Database();
        print $this->db->init()
            ? 'OK'
            : 'ERROR';
        print '</pre>';

        print '<pre>   Create Table: ';
        print $this->db->createTables()
            ? 'OK'
            : 'ERROR';
        print '</pre>';

    }

    private function adminGet()
    {
        $source = $this->router->getVar(0);
        if (!Source::isValidSourceCode($source)) {
            $this->error404('Currency Source Not Found');

            return;
        }
        print '<pre>

        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>
        
        ADMINGET $source

        </pre>';

        $this->db = new Database();
        print_r($this->db);
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
