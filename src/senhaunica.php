<?php

/**
 * oauth-php: Example OAuth client for accessing Google Docs
 *
 * @author BBG
 *
 *
 * The MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/*
 * retorna um array contendo os dados do usuário autenticado pelo login usp digital
 *
 */

namespace Uspdev\Senhaunica;

class Senhaunica
{
    function __construct($oauth) {

        define("REQUEST_TOKEN_URL", $oauth['OAUTH_HOST'] . "/wsusuario/oauth/request_token");
        define("AUTHORIZE_URL", $oauth['OAUTH_HOST'] . "/wsusuario/oauth/authorize");
        define("ACCESS_TOKEN_URL", $oauth['OAUTH_HOST'] . "/wsusuario/oauth/access_token");
        define("USER_DATA_URL", $oauth['OAUTH_HOST'] . "/wsusuario/oauth/usuariousp");

        $curl_options = array(CURLOPT_SSL_VERIFYPEER => false);
        define('OAUTH_TMP_DIR', function_exists('sys_get_temp_dir') ? sys_get_temp_dir() : realpath($_ENV["TMP"]));

        //  Init the OAuthStore
        $options = array(
            'consumer_key' => $oauth['CONSUMER_KEY'],
            'consumer_secret' => $oauth['CONSUMER_SECRET'],
            'server_uri' => $oauth['OAUTH_HOST'],
            'request_token_uri' => REQUEST_TOKEN_URL,
            'authorize_uri' => AUTHORIZE_URL,
            'access_token_uri' => ACCESS_TOKEN_URL,

        );
        // se tiver setado callback id use ele
        $callback_param = (isset($oauth['CALLBACK_ID'])) ? '&callback_id=' . $oauth['CALLBACK_ID'] : '';
    }

    public function login()
    {
        // aqui vai ser chamado sempre na rota de login
   
        include_once "library/OAuthStore.php";
        include_once "library/OAuthRequester.php";
        // Note: do not use "Session" storage in production. Prefer a database
        // storage, such as MySQLi.
        OAuthStore::instance('Session', $options);

        if (empty($_GET["oauth_token"])) {
            //  STEP 1:  If we do not have an OAuth token yet, go get one
            $this->getOauthToken();
        } else {
            //  STEP 2:  Get an access token
            $this->getAccessToken();
            return $this->getUserInfo();
        }
    }

    protected function getOauthToken()
    {
        try {
            $tokenResultParams = OAuthRequester::requestRequestToken($oauth['CONSUMER_KEY'], null, null, 'POST', null, $curl_options);
        } catch (OAuthException2 $e) {
            echo "OAuthException in (fase 1) requestRequestToken:  " . $e->getMessage();
            var_dump($e);
            exit;
        }
        header("Location: " . AUTHORIZE_URL . "?oauth_token=" . $tokenResultParams['token'] . $callback_param);
        exit;
    }

    protected function getAccessToken()
    {
        $oauthToken = $_GET['oauth_token'];
        $oauthVerifier = $_GET['oauth_verifier'];
        $tokenResultParams = $_GET;

        try {
            OAuthRequester::requestAccessT$request = new OAuthRequester(USER_DATA_URL, 'POST');
            $result = $request->doRequest(null, $curl_options);
            if ($result['code'] == 200) {
                $loginUSP = json_decode($result['body'], true);
                return $loginUSP;
            } else {
                echo 'Error: result code not 200';
                print_r($result);
                exit;
            }oken($oauth['CONSUMER_KEY'], $oauthToken, 0, 'POST', $_GET, $curl_options);
        } catch (OAuthException2 $e) {
            echo "OAuthException in (fase 2) requestAccessToken:  " . $e->getMessage();
            var_dump($e);
            // Something wrong with the oauth_token.
            // Could be:
            // 1. Was already ok
            // 2. We were not authorized
            exit;
        }
    }

    protected function getUserInfo()
    {
        $request = new OAuthRequester(USER_DATA_URL, 'POST');
        $result = $request->doRequest(null, $curl_options);
        if ($result['code'] == 200) {
            $loginUSP = json_decode($result['body'], true);
            return $loginUSP;
        } else {
            echo 'Error: result code not 200';
            print_r($result);
            exit;
        }
    }
}