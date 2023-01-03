<?php

class LinkedIn {
    protected $app_id;
    protected $app_secret;
    protected $callback;
    protected $csrf;
    protected $scopes;
    protected $ssl;

    function curl($url, $params, $header, $ssl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = [];
        $headers[] = "Content-Type: ".$header;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        return $result;
    }

    public function __construct(string $app_id, string $app_secret, string $callback, string $scopes, bool $ssl = true)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->callback = $callback;
        $this->scopes = $scopes;
        $this->csrf = random_int(111111,99999999999);
        $this->callback = $callback;
        $this->ssl = $ssl;
    }

    public function getAuthUrl()
    {
        $_SESSION['linkedincsrf'] = $this->csrf;
        return "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=". $this->app_id .
            "&redirect_uri=". $this->callback ."&state=". $this->csrf ."&scope=". $this->scopes;
    }

    public function getAccessToken($code)
    {
        $url = "https://www.linkedin.com/oauth/v2/accessToken";
        $params = [
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'redirect_uri' => $this->callback,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $response = $this->curl($url, http_build_query($params), "application/x-www-form-urlencoded", $this->ssl);

        // On récupère le token et sa durée d'expiration (normalement 60 jours)
        $decode_reponse = json_decode($response);
        $accessToken = $decode_reponse->access_token;
        $expiration = $decode_reponse->expires_in;

        $infosToken = [$accessToken,$expiration];
        return $infosToken;
    }

    public function getPerson($accessToken)
    {
        // Afficher les erreurs mais cacher les avertissements
        // Peu recommandé mais dans notre cas la gestion des erreurs
        // des fonctions ne peut empêcher l'affichage d'un avertissement
        error_reporting(E_ALL ^ E_WARNING);
        $url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))&oauth2_access_token=".$accessToken;
        if($personne = file_get_contents($url, false)) return (json_decode($personne));
        else return "err--requetehttp";
    }

    public function getMail($accessToken)
    {
        error_reporting(E_ALL ^ E_WARNING);
        $url = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))&oauth2_access_token=".$accessToken;
        if($mail = file_get_contents($url, false)) return (json_decode($mail));
        else return "err--requetehttp";
    }

}