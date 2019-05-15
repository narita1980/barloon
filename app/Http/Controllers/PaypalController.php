<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Reporting;


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
//            $params = array('count' => 10, 'start_index' => 5);
//            $payments = Payment::all($params, $this->apiContext);

            $params = array(
                'start_date' => '2019-05-01T00:00:00-0900',
                'end_date' => '2019-05-31T23:59:59-0900',
//                'transaction_status' => 'S',
            );
            $transactions = Reporting::all($params, $this->apiContext);

        } catch (Exception $ex) {
        }

//        return response()->json(['apple'=>'red','peach'=>'pink']);
        return response()->json($transactions, 200, array(), JSON_PRETTY_PRINT);
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
