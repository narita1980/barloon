<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        /** PayPal api context **/
        $this->apiContext = $this->getPayPalApiContext(config('paypal.client_id'), config('paypal.secret'));
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public function index(Request $request)
    {
        try {
            $params = array('count' => 10, 'start_index' => 5);
            $payments = Payment::all($params, $this->apiContext);
        } catch (Exception $ex) {
        }

//        return response()->json(['apple'=>'red','peach'=>'pink']);
        return response()->json($payments);

    }

    /**
     * Helper method for getting an APIContext for all calls
     *
     * @param string $clientId Client ID
     * @param string $clientSecret Client Secret
     * @return PayPal\Rest\ApiContext
     */
    function getPayPalApiContext($clientId, $clientSecret)
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
            )
        );

        return $apiContext;
    }
}
