<?php


class SMSHelper
{
    private static $apiUrl = "https://api.vatansms.net/api/v1/";

    /**
     * Kullanıcı bilgilerini getirir.
     *
     * @param [type] $apiId
     * @param [type] $apiKey
     * @return array
     */
    public static function getUserInformation($apiId, $apiKey): ?array
    {
        return self::request("POST", self::urlPrepend("user/information"), [
            "api_id" => $apiId,
            "api_key" => $apiKey
        ]);
    }

    /**
     * Tarih bazlı sms raporlarını getirir.
     *
     * @param [type] $apiId
     * @param [type] $apiKey
     * @param [type] $startDate
     * @param [type] $endDate
     * @return array|null
     */
    public static function getSMSReportDate($apiId, $apiKey, $startDate, $endDate): ?array
    {
        return self::request("POST", self::urlPrepend("report/between"), [
            "api_id" => $apiId,
            "api_key" => $apiKey,
            "start_date" => $startDate,
            "end_date" => $endDate
        ]);
    }

    /**
     * Id bazlı sms raporlarını getirir.
     *
     * @param [type] $apiId
     * @param [type] $apiKey
     * @param [type] $startDate
     * @param [type] $endDate
     * @return array|null
     */
    public static function getSMSReportId($apiId, $apiKey, $reportId, $page = 1): ?array
    {
        return self::request("POST", self::urlPrepend("report/detail?page=$page&pageSize=10"), [
            "api_id" => $apiId,
            "api_key" => $apiKey,
            "report_id" => $reportId,
        ]);
    }

    /**
     * Kullanıcının sms başlığını getirir.
     *
     * @param string $apiId
     * @param string $apiKey
     * @return array|null
     */
    public static function getUserSenders($apiId, $apiKey): ?array
    {
        return self::request("POST", self::urlPrepend("senders"), [
            "api_id" => $apiId,
            "api_key" => $apiKey
        ]);
    }

    /**
     * Sms gönderir one to n.
     *
     * @param string $apiId
     * @param string $apiKey
     * @param string $sender
     * @param string $numbers
     * @param array $message
     * @return array|null
     */
    public static function sendSmsOneToN($apiId, $apiKey, $sender, string $numbers, string $message): ?array
    {
        $numbers = explode(",", $numbers);

        return self::request("POST", self::urlPrepend("1toN"), [
            "api_id" => $apiId,
            "api_key" => $apiKey,
            "sender" => $sender,
            "message_type" => "turkce",
            "message" => $message,
            "phones" => VSPH::repairPhones($numbers)
        ]);
    }


    /**
     * Api url ini hazırlar.
     *
     * @param string $url
     * @return string
     */
    public static function urlPrepend(string $url): string
    {
        return self::$apiUrl . $url;
    }

    /**
     * Apiye request atar.
     *
     * @param [type] $method
     * @param [type] $url
     * @param [type] $data
     * @return void
     */
    public static function request(string $method, string $url, array $data): ?array
    {
        $result = null;

        if ($method == "POST") {
            $result = wp_remote_post($url, [
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($data),
                'cookies' => []
            ])["body"];
        }

        return json_decode($result, true);
    }
}
