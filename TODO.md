# Future Developments

* db migration script
* per-page `<title>`
* unit tests
* travis-ci build
* full documentation
* refactor feed processing to XPATH
* refactor layout to CSS GRID
* add guzzle status to verbosity > 1 in admin Feeds
* lower timeout, handle Uncaught GuzzleHttp\Exception\RequestException in admin Feeds
* /about/ currency list from DB, use config only for name
* custom config adminIP - allow multiple IPs
* option disable custom header/footer on 404 responses
* number format - remove/grey-out trailing zeros

## new feeds

* US Federal Reserve
  * <https://www.federalreserve.gov/feeds/h10_data.htm>
* Bank of Canada
  * <https://www.bankofcanada.ca/valet/fx_rss/>
  * <https://www.bankofcanada.ca/stats/assets/rates_rss/noon/en_all.xml>
  * <https://www.bankofcanada.ca/stats/assets/rates_rss/closing/en_all.xml>
* Reserve Bank of Australia
  * <https://www.rba.gov.au/rss/rss-cb-exchange-rates.xml>
* Bank of Thailand
  * <https://www2.bot.or.th/RSS/fxrates/fxrate-all.xml>
* National Bank of Denmark
  * <https://www.nationalbanken.dk/_vti_bin/DN/DataService.svc/CurrencyRatesXML?lang=en>
* Central Bank of Myanmar
  * <https://forex.cbm.gov.mm/api/latest>
* Central Bank of Malaysia
  * <https://www.bnm.gov.my/index.php?tpl=fxrates.tsl>
* Monetary Authority of Singapore
  * <https://secure.mas.gov.sg/msb/ExchangeRates.aspx>
* Bank of Norway
  * <https://www.norges-bank.no/en/rss-feeds/>
* Bank of Mexico
  * <http://www.anterior.banxico.org.mx/rss/rss-chanels-.html>
* Bank of Sweden
  * <https://swea.riksbank.se/sweaWS/docs/api/quick.htm#>

## misc

More info:
<https://github.com/attogram/currency-exchange-rates>
