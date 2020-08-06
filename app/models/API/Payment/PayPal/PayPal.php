<?php


namespace App\models\API\Payment\PayPal;

use App\models\PerformAction;

class PayPal {
    private $facilitator;
    private $currencyCode = "RUB";

    public function __construct($facilitator, $currencyCode) {
        $this->facilitator = $facilitator;
        $this->currencyCode = $currencyCode;
    }

    public function handler() {
        $ipn = new PayPalIPN();

        // Use the sandbox endpoint during testing.
        $ipn->useSandbox();
        $verified = $ipn->verifyIPN();

        if($verified) {
            $request = $this->getRequest();
            PerformAction::pay(isset($request['custom']) ? $request['custom'] : null,
                isset($request['amount']) ? $request['amount'] : null);
        }
        // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
        header("HTTP/1.1 200 OK");
    }

    private function getRequest() {
        $raw_post_data = file_get_contents('php://input');
        return $raw_post_array = explode('&', $raw_post_data);
    }

    public function getLink($amount, $description, $comment, $successUrl) {
        return "https://www.sandbox.paypal.com/cgi-bin/websc?amount=".$amount.
            "&cmd=_xclick&business=".$this->facilitator.
            "&item_name=".$description.
            "&no_shipping=1&return=".$successUrl.
            "&custom=".$comment.
            "&currency_code=".$this->facilitator.
            "&lc=US&bn=PP-BuyNowBF";
    }
}
