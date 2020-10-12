<?php

namespace App\Service;


use Codenixsv\CoinGeckoApi\CoinGeckoClient;


class CoinGecko
{

    private $clients;


    public function __construct()
    {

        $this->clients = new CoinGeckoClient();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function dataCurrent()
    {
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $data[$i] = $this->clients->coins()->getMarkets('usd', ['page' => $i, 'price_change_percentage' => '24h', 'order' => 'volume_desc']);
        }

        return $data;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function history()
    {


        $lists = $this->dataCurrent();
        $dataAll = [];
        foreach ($lists as $key => $list) {
            foreach ($list as $li) {
                //dump($li);
                $dataAll[$li['id']] = ['volume_day' => $li['total_volume'],
                    'max_supply' => $li['circulating_supply'],
                    'pourcentage_market_cap' => round($li['market_cap_change_percentage_24h'], 2),
                    'price_change_pourcentage_24h' => round($li['price_change_percentage_24h_in_currency'], 2)];
            }
        }

        return $dataAll;
    }

    /**
     * @param $volumeDay
     * @param $volumeYesterday
     * @return float
     */
    private function pourcentage($volumeDay, $volumeYesterday)
    {
        $resultatBrut = ($volumeDay / $volumeYesterday) - 1;
        $resultat = $resultatBrut * 100;


        return round($resultat);


    }

    /**
     * @return string
     */
    private function yesterdayDate()
    {

        $mois = date("m");
        $jour = date("d");
        $annee = date("Y");

        $hier = getdate(mktime(0, 0, 0, $mois, $jour - 1, $annee));
        if ($hier['mon'] < 10)
            $hier['mon'] = "0" . $hier['mon'];
        if ($hier['mday'] < 10)
            $hier['mday'] = "0" . $hier['mday'];
        $hier = $hier['mday'] . "-" . $hier['mon'] . "-" . $hier['year'];

        return $hier;
    }

    /**
     * @return array
     */
    public function calculFormatage()
    {

        $datum = $this->history();
        $newData = [];
        foreach ($datum as $key => $data) {
            if (!is_null($data['pourcentage_market_cap'])) {
                if ($data['pourcentage_market_cap'] > 10 || $data['pourcentage_market_cap'] < -10) {
                    $newData[$key] = $data;
                }
            }
        }

        return $newData;

    }

    private function pourcentageVolume()
    {
        /*
$pourcentageVolume = [];
foreach ($data as $key => $dat) {
    $pourcent = $this->pourcentage($dat['volume_day'], $dat['volume_yesterday']);
    if ($pourcent > 45 || $pourcent < 0) {
        $pourcentageVolume[$key] = $pourcent;
    }
} */
    }


}
