<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;

use function header;
use function method_exists;

class CurrencyExchangeRates
{
    /** @var string Version*/
    const VERSION = '0.0.10-alpha';

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
        $this->router->allow('/admin/', 'admin');
        $this->router->allow('/admin/database/', 'adminDatabase');
        $this->router->allow('/admin/feed/?/', 'adminFeed');

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

    private function admin()
    {
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>';

        print "\n\n\tFeeds:\n";

        foreach (Config::$feeds as $code => $feed) {
            print "\t" . '<a href="feed/' . $code . '/">' . $feed['name'] . "</a>\n";
        }

        print "\n\n\t" . '<a href="database/">Database</a>';
        print "\n\n\n</pre>";
    }

    private function adminDatabase()
    {
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>
        <a href="' . $this->router->getHomeFull() . 'admin/database/">' . $this->router->getHomeFull() . 'admin/database/</a>
        
        
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

    private function adminFeed()
    {
        $feedCode = $this->router->getVar(0);
        if (!Config::isValidFeed($feedCode)) {
            $this->error404('Feed Not Found');

            return;
        }

        $class = "\\Attogram\\Currency\\Feeds\\" . $feedCode;
        if (!class_exists($class)) {
            $this->error404('Feed Class Not Found');

            return;
        }

        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>'
        . "\n\n";

        $api = Config::getFeedApi($feedCode);
        $name = Config::getFeedName($feedCode);

        print "\t\nFeed: $name " . '<a href="' . $api . '">' . $api . '</a>' . "\n";
        print

        (new $class($api))->process();

        print "\n\n\n</pre>";
    }

    private function currency()
    {
        $currency = $this->router->getVar(0);
        if (!Config::isValidCurrency($currency)) {
            $this->error404();

            return;
        }
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>
        
        CURRENCY ' . "$currency";
    }

    private function currencyPair()
    {
        $source = $this->router->getVar(0);
        $target = $this->router->getVar(1);
        if (!Config::isValidCurrency($source) || !Config::isValidCurrency($target)) {
            $this->error404();

            return;
        }
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>
        
        CURRENCYPAIR ' . "$source $target";
    }
}
