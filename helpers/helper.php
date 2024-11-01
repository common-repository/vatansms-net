<?php


class VSPH
{
    /**
     * Post parametresine state ve mesaj ekler
     *
     * @param string $state
     * @param string $message
     * @return void
     */
    public static function postState(string $state, string $message): void
    {
        $_POST["state"] = $state;
        $_POST["state_message"] = $message;
    }

    /**
     * Post varmı kontrol helperı
     *
     * @param array $arr
     * @return boolean
     */
    public static function isPost(array $arr): bool
    {
        return (count($arr) > 0 ? true : false);
    }




    /**
     * Telefon numarası düzenleyici
     *
     * @param array $phones
     * @return array
     */
    public static function repairPhones(array $phones): array
    {
        return array_map(function($p) {
            $p = str_replace(" ", "", $p);

            if (substr($p, 0, 1) == 0 && strlen(substr($p, 1, strlen($p))) == 10) {
                return substr($p, 1, strlen($p));
            } elseif (substr($p, 0, 1) == 5 && strlen($p) == 10) {
                return $p;
            }
        }, $phones);
    }
}
