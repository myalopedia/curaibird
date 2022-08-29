<?php
    // SAMPLE HIT API iPaymu v2 PHP //
    class Tripay{
        function __construct() {
            $this->privateKey = 'brOMJ-bv59X-D8qQS-ElSXI-uuCIy';
            $this->merchantCode = 'T4512';
            $this->merchantRef = 'INV55567';
            $this->apiKey = "DEV-hMXDCNzSgQUddsLRJS3bVNQqAJXs7s96yR1FhpRj";
        }

        function make_signature($amount)
        {
            $signature = hash_hmac('sha256', $this->merchantCode.$this->merchantRef.$amount, $this->privateKey);

            return $signature;
        }

        function get_daftar_metode($select)
        {
            $apiKey = $this->apiKey;

            if($select) {
                // $payload = ['code' => 'BRIVA'];
                $payload = $select;
                $url = 'https://tripay.co.id/api-sandbox/merchant/payment-channel?'.http_build_query($payload);
            }
            else {
                $url = 'https://tripay.co.id/api-sandbox/merchant/payment-channel';
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            return empty($error) ? $response : $error;
        }

        function calculate_total($metode, $amount) {
            $apiKey = $this->apiKey;

            $payload = [
                'code' => $metode,
                'amount' => $amount,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/fee-calculator?'.http_build_query($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            return empty($error) ? $response : $error;

        }

        function getAuthentication()
        {
            $return = array(
                'apiKey' => $this->apiKey,
                'privateKey' => $this->privateKey,
                'merchantCode' => $this->merchantCode,
                'merchantRef' => $this->merchantRef
            );

            return $return;
        }
        
        function detailTrasnaksi($reference)
        {
            $apiKey = $this->apiKey;

            $payload = ['reference'	=> $reference];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/detail?'.http_build_query($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            return empty($error) ? $response : $error;
        }
    }
?>