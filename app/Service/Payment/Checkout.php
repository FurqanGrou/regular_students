<?php

namespace App\Service\Payment;

use App\Models\Subscribe;
use Carbon\Carbon;
use Checkout\CheckoutApi;
use Checkout\Library\Exceptions\CheckoutHttpException;
use Checkout\Library\Exceptions\CheckoutModelException;
use Checkout\Models\Address;
use Checkout\Models\Customer;
use Checkout\Models\Payments\BillingDescriptor;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\Risk;
use Checkout\Models\Payments\Shipping;
use Checkout\Models\Payments\ThreeDs;
use Checkout\Models\Payments\TokenSource;
use Checkout\Models\Phone;
use Checkout\Models\Tokens\Card;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Session;

class Checkout
{
//    private $sandbox = 'https://api.sandbox.checkout.com';
//    private $secret = 'sk_test_7c21900d-0f6b-4395-af84-9508b39fd5c7';
//    private $public = 'pk_test_7f411d80-c340-411c-a6e6-9578bf634c19';

    private $sandbox = 'https://api.checkout.com/payments';
    private $secret = 'sk_8cbe6cf1-3871-4c1c-ae84-cd49b7e2af90';
    private $public = 'pk_562d50cb-b790-4b10-893b-641edb7df296';

    /**
     * not required
     * @return array|bool|float|int|object|string|null
     * @throws GuzzleException
     */
    public function checkout()
    {
        $client = new Client(['base_uri' => $this->sandbox]);

        $response = $client->request('POST', '/payment-links',
            [
                'json' => [
                    "amount" => 29500,
                ], 'headers' => [
                'Authorization' => "pk_562d50cb-b790-4b10-893b-641edb7df296"
            ]
            ]);
        $data = \GuzzleHttp\json_decode($response->getBody()->getContents());
        return $data->status;
    }

    public function payment(string $token, $customer, $amount)
    {

        // now()
        $now = Carbon::now();
        // SELECT max(number) from subscriptions where year(created_at) = ?

        $reference_number = Subscribe::whereYear('created_at', '=', $now->year)->max('reference_number');
        $reference_number = $reference_number ? intval($reference_number) + 1 : $now->year . '0001';

        $checkout = new CheckoutApi($this->secret);
        $method = new TokenSource($token);

        $payment = new Payment($method, 'USD');
        $payment->customer = $this->customer($customer['email'], $customer['name']);
//        $payment->shipping = $this->shipping([
//            '14-17 Wells Mews',
//            'Fitzrovia',
//            'London',
//            'London',
//            'W1T 3HF',
//            'UK'], [
//            '0044', '02073233888'
//        ]);
        $payment->billing_descriptor = new BillingDescriptor('Dynamic desc charge', 'City charge');
//        $payment->amount = 29500;
        $payment->amount = $amount;
        $payment->capture = true;
        $payment->reference = $reference_number;
        $payment->success_url = url('/thank-you');
        $payment->failure_url = url('/thank-you');
        $threeDs = new ThreeDs(true);
        $threeDs->attempt_n3d = true;
        $payment->threeDs = $threeDs;

        $payment->risk = new Risk(false);
//        $payment->setIdempotencyKey('123');

        try {
            $details = $checkout->payments()->request($payment);

            Session::put('payment_id', $details->id);
            Session::put('payment_status', $details->status);
            Session::put('reference_number', $reference_number);

//            $redirection = $details->getRedirection();
//            if ($redirection) {
//                return redirect($redirection);
//            }

            return $details;
        } catch (CheckoutModelException $ex) {
            return $ex->getErrors();
        } catch (CheckoutHttpException $ex) {
            return $ex->getErrors();
        }
    }

    /**
     * @return string
     */
    private function requestToken(): string
    {
        $checkout = new CheckoutApi($this->secret, -1, $this->public);
        $card = new Card('4600140002200392', '6', '2025');
        $token = $checkout->tokens()
            ->request($card);
        return $token->token;
// OLD Way
//        $client = new Client(['base_uri' => $this->sandbox]);
//
//        $response = $client->request('POST', '/tokens', [
//            'json' => [
//                "type" => "card",
//                "number" => "4600140002200392",
//                "expiry_month" => 6,
//                "expiry_year" => 2025,
//                "name" => "Bruce Wayne",
//                "cvv" => "956",
//                "billing_address" => [
//                    "address_line1" => "Checkout.com",
//                    "address_line2" => "90 Tottenham Court Road",
//                    "city" => "London",
//                    "state" => "London",
//                    "zip" => "W1T 4TJ",
//                    "country" => "GB"
//                ],
//                "phone" => [
//                    "number" => "4155552671",
//                    "country_code" => "+1"
//                ],
//                "token_data" => [
//                    "version" => "EC_v1",
//                    "data" => "t7GeajLB9skXB6QSWfEpPA4WPhDqB7ekdd+F7588arLzvebKp3P0TekUslSQ8nkuacUgLdks2IKyCm7U3OL/PEYLXE7w60VkQ8WE6FXs/cqHkwtSW9vkzZNDxSLDg9slgLYxAH2/iztdipPpyIYKl0Kb6Rn9rboF+lwgRxM1B3n84miApwF5Pxl8ZOOXGY6F+3DsDo7sMCUTaJK74DUJJcjIXrigtINWKW6RFa/4qmPEC/Y+syg04x7B99mbLQQzWFm7z6HfRmynPM9/GA0kbsqd/Kn5Mkqssfhn/m6LuNKsqEmbKi85FF6kip+F17LRawG48bF/lT8wj/QEuDY0G7t/ryOnGLtKteXmAf0oJnwkelIyfyj2KI8GChBuTJonGlXKr5klPE89/ycmkgDl+T6Ms7PhiNZpuGEE2QE=",
//                    "signature" => "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",
//                    "header" => [
//                        "ephemeralPublicKey" => "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEX1ievoT8DRB8T5zGkhHZHeDr0oBmYEgsDSxyT0MD0IZ2Mpfjz2LdWq6LUwSH9EmxdPEzMunsZKWMyOr3K/zlsw==",
//                        "publicKeyHash" => "tqYV+tmG9aMh+l/K6cicUnPqkb1gUiLjSTM9gEz6Nl0=",
//                        "transactionId" => "3cee89679130a4b2617c76118a1c62fd400cd45b49dc0916d5b951b560cd17b4"
//                    ]
//                ]
//            ],
//            'headers' =>
//                [
//                    'Authorization' => "$this->public"
//                ]]);
//        $data = \GuzzleHttp\json_decode($response->getBody()->getContents());
//        return $data->token;
    }

    /**
     * @param string $email
     * @param string $name
     * @return Customer
     */
    private function customer(string $email, string $name): Customer
    {
        $customer = new Customer();
        $customer->email = $email;
        $customer->name = $name;
        return $customer;
    }

    /**
     * @param array $address
     * @param array $phone
     * @return Shipping
     */
    private function shipping(array $address, array $phone): Shipping
    {
        return new Shipping($this->address(...$address), $this->phone(...$phone));
    }

    /**
     * @param string $address_line1
     * @param string $address_line2
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $country
     * @return Address
     */
    private function address(string $address_line1, string $address_line2, string $city, string $state, string $zip, string $country): Address
    {
        $address = new Address();
        $address->address_line1 = $address_line1;
        $address->address_line2 = $address_line2;
        $address->city = $city;
        $address->state = $state;
        $address->zip = $zip;
        $address->country = $country;
        return $address;
    }

    /**
     * @param string $countryCode
     * @param string $number
     * @return Phone
     */
    private function phone(string $countryCode, string $number): Phone
    {
        $phone = new Phone();
        $phone->country_code = $countryCode;
        $phone->number = $number;
        return $phone;
    }

}
