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
    const VERSION = '0.0.19-alpha';

    /** @var Router */
    private $router;

    private $gitRepo = 'https://github.com/attogram/currency-exchange-rates';

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
     * @throws Exception
     */
    protected function home()
    {
        $this->displayHeader();
        $database = new Database();
        $rates = $database->query('SELECT * FROM rates ORDER BY last_updated DESC LIMIT 100');
        print $this->displayRates($rates);
        $this->displayFooter();
    }

    protected function admin()
    {
        $this->displayHeader(true);
        print "\n\n\tFeeds:\n";
        foreach (Config::$feeds as $code => $feed) {
            print "\t" . '<a href="feed/' . $code . '/">' . $feed['name'] . "</a>\n";
        }
        $this->displayFooter();
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
        $this->displayHeader(true);
        $api = Config::getFeedApi($feedCode);
        $name = Config::getFeedName($feedCode);
        print "Feed: $name " . '<a href="' . $api . '">' . $api . '</a>' . "\n";
        new $class($api);
        $this->displayFooter();
    }

    /**
     * @throws Exception
     */
    protected function currency()
    {
        $currency = $this->router->getVar(0);
        if (!Config::isValidCurrency($currency)) {
            $this->error404();

            return;
        }
        $this->displayHeader();
        $database = new Database();
        $rates = $database->query(
            'SELECT * FROM rates WHERE source = :s OR target = :t ORDER BY last_updated DESC LIMIT 100',
            ['s' => $currency, 't' => $currency]
        );
        print "$currency Rates:\n\n";
        print $this->displayRates($rates);
        $this->displayFooter();
    }

    /**
     * @throws Exception
     */
    protected function currencyPair()
    {
        $source = $this->router->getVar(0);
        $target = $this->router->getVar(1);
        if (!Config::isValidCurrency($source) || !Config::isValidCurrency($target)) {
            $this->error404();

            return;
        }
        $this->displayHeader();
        $database = new Database();
        $rates = $database->query(
            'SELECT * FROM rates WHERE source = :s AND target = :t ORDER BY last_updated DESC LIMIT 100',
            ['s' => $source, 't' => $target]
        );
        print "$source/$target Rates:\n\n";
        print $this->displayRates($rates);
        $this->displayFooter();
    }

    /**
     * @param string $message
     */
    private function error404(string $message = 'Page Not Found')
    {
        header('HTTP/1.0 404 Not Found');
        $this->displayHeader();
        print "\n\n\n\n404 $message\n\n\n\n";
        $this->displayFooter();
    }

    /**
     * @param array $rates
     * @return string
     */
    private function displayRates(array $rates)
    {
        if (empty($rates)) {
            return "Currency exchange rates not available\n";
        }
        $display = "Date\t\tRate \t\tSource\tTarget\t<small>Feed\t\t\tlast_updated</small>\n";
        $display .= "----------\t------------\t---\t---\t<small>---------------\t-----------------------</small>\n";
        foreach ($rates as $rate) {
            $display .= $rate['day'] . "\t"
                . round($rate['rate'], 10)
                . ((strlen($rate['rate']) > 7) ? "\t" : "\t\t")
                . '<a href="' . $this->router->getHome() . $rate['source'] . '/">' . $rate['source'] . "</a>\t"
                . '<a href="' . $this->router->getHome() . $rate['target'] . '/">' . $rate['target'] . "</a>\t"
                . '<small>'
                . $rate['feed']
                . ((strlen($rate['feed']) > 10) ? "\t" : "\t\t")
                . $rate['last_updated'] . ' UTC'
                . "</small>\n";
        }

        return $display;
    }

    /**
     * @param bool $isAdmin
     */
    private function displayHeader(bool $isAdmin = false)
    {
        print '
<html lang="en">
<head>
<title>Currency Exchange Rates</title>
<style>
body { margin:25px 50px 50px 50px; }
a { color:darkblue; text-decoration:none; }
</style>
</head>
<body><pre>';
        $this->displayMenu($isAdmin);
        print "\n-----------------------\n\n";
    }

    private function displayFooter()
    {
        print "\n\n-----------------------\n";
        $this->displayMenu();
        print "\n\n" . gmdate('Y-m-d H:i:s') . " UTC\n\n";
        print '<a href="' . $this->gitRepo . '">' . $this->gitRepo . "</a>\n\n";
        print '</pre></body></html>';
    }

    /**
     * @param bool $isAdmin
     */
    private function displayMenu(bool $isAdmin = false)
    {
        print '<a href="' . $this->router->getHomeFull() . '">Currency Exchange Rates</a>';
        if ($isAdmin) {
            print ' - <a href="' . $this->router->getHomeFull() . 'admin/">admin</a>';
        }
    }

}
