<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;
use Exception;
use Throwable;

use function class_exists;
use function count;
use function header;
use function method_exists;

class CurrencyExchangeRates
{
    use CustomizationTrait;

    /** @var string Version*/
    const VERSION = '1.0.0';

    /** @var Database|null */
    protected $database;

    /** @var Router */
    protected $router;

    /** @var string Git Repository */
    protected $gitRepo = 'https://github.com/attogram/currency-exchange-rates';

    public function __construct()
    {
        $this->loadConfig();
        $this->route();
    }

    protected function route()
    {
        $this->router = new Router();
        $this->router->setForceSlash(true);
        $this->router->allow('/', 'home');
        $this->router->allow('/about/', 'about');
        $this->router->allow('/about/?/', 'feedInfo');
        $this->router->allow('/?/', 'currency');
        $this->router->allow('/?/?/', 'currencyPair');
        if ($this->isAdmin()) {
            $this->router->allow('/admin/', 'admin');
            $this->router->allow('/admin/feed/?/', 'adminFeed');
        }
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
        $this->database = new Database();
        $this->displayCurrencyCodes();
        $pairCount = $this->displayCurrencyPairs();
        print "\n\nExchange rates as of " . gmdate('Y-m-d H:i:s') . " UTC\n\n";
        $rates = $this->database->query('SELECT * FROM rates ORDER BY last_updated DESC LIMIT ' . $pairCount);
        print Format::formatRates($rates, $this->router->getHome());
        $this->displayFooter();
    }

    protected function about()
    {
        $this->displayHeader();
        print 'This site incorporates currency exchange data retrieved from '
            . count(Config::$feeds) . " sources:\n\n";
        foreach (Config::$feeds as $code => $feed) {
            print ' - <a href="' . $this->router->getHome() . 'about/' . $code . '">'
                . 'The ' . $feed['name'] . '</a>' . ' (<a href="' . $this->router->getHome() . $feed['currency'] . '/">'
                . $feed['currency'] . '</a>)' . "\n";
        }
        print "\nwith " . count(Config::$currencies) . " available currencies:\n\n";
        foreach (Config::$currencies as $code => $currency) {
            print ' - <a href="' . $this->router->getHome() . $code . '/">'
                . $code . '</a> (' . $currency['name'] . ")\n";
        }
        $this->displayFooter();
    }

    /**
     * @throws Exception
     */
    protected function feedInfo()
    {
        $feedCode = $this->router->getVar(0);
        if (!Config::isValidFeed($feedCode)) {
            $this->error404('Page Not Found');

            return;
        }
        $this->displayHeader();
        print 'About The ' . Config::$feeds[$feedCode]['name'] . "\n"
            . '</pre>' . Config::$feeds[$feedCode]['about'] . "<pre>\n\n"
            . 'Currency: <b><a href="' . $this->router->getHome() . Config::$feeds[$feedCode]['currency']
            . '/">' . Config::$feeds[$feedCode]['currency'] . "</a></b>\n\n"
            . $this->feedInfoPairs(Config::$feeds[$feedCode]['currency']) . "\n\n"
            . 'Website: <a href="'. Config::$feeds[$feedCode]['home'] . '">'
            . Config::$feeds[$feedCode]['home'] . "</a>\n\n"
            . 'API Endpoint:  <a href="' . Config::$feeds[$feedCode]['api'] . '">'
            . Config::$feeds[$feedCode]['api'] . "</a>\n"
            . 'API Frequency: ' . Config::$feeds[$feedCode]['freq'] . "\n\n";
        $this->displayFooter();
    }

    /**
     * @param string $source
     * @return string
     * @throws Exception
     */
    protected function feedInfoPairs(string $source)
    {
        $this->database = new Database();
        $pairsQ = $this->database->query(
            'SELECT DISTINCT source, target FROM rates WHERE source = :s ORDER BY target',
            ['s' => $source]
        );
        $pairsA = [];
        foreach ($pairsQ as $pair) {
            $pairsA[] .= implode('/', $pair);
        }
        $pairDisplay = '';
        $break = 0;
        foreach ($pairsA as $pair) {
            $pairDisplay .= '<a href="' . $this->router->getHome() . $pair . '"/>' . $pair . '</a>, ';
            if (++$break > 7) {
                $pairDisplay .= "\n";
                $break = 0;
            }
        }

        return count($pairsA) . " Currency Pairs:\n\n" . $pairDisplay;
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
        $this->database = new Database();
        $rates = $this->database->query(
            'SELECT * FROM rates WHERE source = :s OR target = :t ORDER BY last_updated DESC LIMIT 100',
            ['s' => $currency, 't' => $currency]
        );
        print $currency . ' (' . Config::getFeedCurrencyName($currency) . ") Exchange Rates:\n\n"
            . Format::formatRates($rates, $this->router->getHome());
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
        $this->database = new Database();
        $rates = $this->database->query(
            'SELECT * FROM rates WHERE source = :s AND target = :t ORDER BY last_updated DESC LIMIT 100',
            ['s' => $source, 't' => $target]
        );
        print '<a href="' . $this->router->getHome() . $source . '/">' . "$source</a>"
            . '/<a href="' . $this->router->getHome() . $target . '/">' . "$target</a>"
            . " Rates:\n\n" . Format::formatRates($rates, $this->router->getHome());
        $this->displayFooter();
    }

    /**
     * @param string $message
     */
    protected function error404(string $message = 'Page Not Found')
    {
        header('HTTP/1.0 404 Not Found');
        $this->displayHeader();
        print "\n\n\n\n404 $message\n\n\n\n";
        $this->displayFooter();
    }

    /**
     * @throws Exception
     */
    protected function displayCurrencyCodes()
    {
        $currencies = $this->database->getCurrencyCodes();
        print count($currencies) . " Currencies\n\n";
        $break = 0;
        foreach ($currencies as $currency) {
            print '<a href="' . $this->router->getHome() . $currency['currency'] . '/">'
                . $currency['currency'] . '</a>, ';
            if (++$break > 15) {
                print "\n";
                $break = 0;
            }
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function displayCurrencyPairs()
    {
        $pairs = $this->database->getCurrencyPairs();
        print "\n\n" . count($pairs) . " Currency Pairs\n\n";
        $break = 0;
        foreach ($pairs as $pair) {
            $upair = $pair['source'] . '/' . $pair['target'];
            print '<a href="' . $this->router->getHome() . $upair . '/">' . $upair . '</a>, ';
            if (++$break > 8) {
                print "\n";
                $break = 0;
            }
        }

        return count($pairs);
    }

    protected function displayHeader()
    {
        print '<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>' . $this->config['title'] . '</title>
<style>
body { margin:25px 50px 50px 50px; }
a, a:visited { color:darkblue; text-decoration:none; }
a:hover { color:black; background-color:yellow; }
</style>
</head>
<body>';
        $this->includeCustom('header.php');
        print'<pre>';
        $this->displayMenu();
        print "\n\n\n";
    }

    protected function displayFooter()
    {
        print "\n\n\n";
        $this->displayMenu();
        print "\n\n\n"
            . '<small>Powered by <a href="' . $this->gitRepo . '">attogram/currency-exchange-rates</a>'
            . ' v' . self::VERSION . "</small>\n\n" . '</pre>';
        $this->includeCustom('footer.php');
        print '</body></html>';
    }

    protected function displayMenu()
    {
        print '<b><a href="' . $this->router->getHomeFull() . '">' . $this->config['title'] . '</a></b>';
        print ' - <a href="' . $this->router->getHomeFull() . 'about/">about</a></b>';
        if ($this->isAdmin()) {
            print ' - <em><a href="' . $this->router->getHomeFull() . 'admin/">admin</a></em>';
        }
    }

    /**
     * @return bool
     */
    protected function isAdmin()
    {
        if (!empty($this->config['adminIP'])
            && $this->config['adminIP'] === $this->router->getServer('REMOTE_ADDR')
        ) {
            return true;
        }

        return false;
    }

    protected function admin()
    {
        $this->displayHeader();
        print "Retrieve Feed Data:\n\n";
        foreach (Config::$feeds as $code => $feed) {
            print ' - <a href="feed/' . $code . '/">' . $feed['name'] . "</a>\n\n";
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
        $this->displayHeader();
        $api = Config::getFeedApi($feedCode);
        $name = Config::getFeedName($feedCode);
        print "Feed: $name " . '<a href="' . $api . '">' . $api . '</a>' . "\n";
        new $class($api);
        $this->displayFooter();
    }
}
