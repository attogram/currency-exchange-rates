<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;
use Exception;
use Throwable;

use function header;
use function method_exists;

class CurrencyExchangeRates
{
    /** @var string Version*/
    const VERSION = '0.0.15-alpha';

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
        $this->router->allow('/admin/feed/?/', 'adminFeed');

        $match = $this->router->match();
        if ($match && method_exists($this, $match)) {
            try {
                $this->{$match}();
            } catch (Throwable $error) {
                print "\nERROR: " . $error->getMessage();
            }
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

    /**
     * @throws Exception
     */
    protected function home()
    {
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        
        <a href="CHF/">CHF</a>    <a href="CHF/USD/">CHF/USD</a>
        <a href="EUR/">EUR</a>    <a href="EUR/USD/">EUR/USD</a>
        <a href="ILS/">ILS</a>    <a href="ILS/USD/">ILS/USD</a>
        <a href="RUB/">RUB</a>    <a href="RUB/USD/">RUB/USD</a>

        <a href="admin/">admin</a>';

        $database = new Database();
        print "\n\nTest: ";

        $database->query(
            'SELECT * FROM rates ORDER BY last_updated DESC LIMIT 10'
        );

        print "\n\n</pre>";
    }

    protected function admin()
    {
        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>';

        print "\n\n\tFeeds:\n";

        foreach (Config::$feeds as $code => $feed) {
            print "\t" . '<a href="feed/' . $code . '/">' . $feed['name'] . "</a>\n";
        }
        print "\n\n\n</pre>";
    }

    protected function adminFeed()
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

        $api = Config::getFeedApi($feedCode);
        $name = Config::getFeedName($feedCode);

        print '<pre>
        <a href="' . $this->router->getHomeFull() . '">' . $this->router->getHomeFull() . '</a>
        <a href="' . $this->router->getHomeFull() . 'admin/">' . $this->router->getHomeFull() . 'admin/</a>
        <a href="' . $this->router->getCurrentFull() . '">' . $this->router->getCurrentFull() . '</a>'
        . "\n\n";

        print "\t\nFeed: $name " . '<a href="' . $api . '">' . $api . '</a>' . "\n";

        new $class($api);

        print "\n\n\n</pre>";
    }

    protected function currency()
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

    protected function currencyPair()
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
