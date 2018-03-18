<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as HttpRequest;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('@AppBundle/main/index.html.twig');
    }

    /**
     * @Route("/scan-data", name="scan_data")
     */
    public function scanDataAction(Request $request)
    {
        $data = json_decode($request->get('content'));

        $data = [
            'shop' => $data->shop,
            'bank' => $data->bank,
            'sum' => $data->sum,
            'currency' => 'EUR',
            'iban' => $data->iban
        ];

        $session = new Session();

        $session->set('shop', $data['shop']);
        $session->set('bank', $data['bank']);
        $session->set('sum', $data['sum']);
        $session->set('currency', $data['currency']);
        $session->set('iban', $data['iban']);

        return $this->json([
            'success' => true
        ]);
    }

    /**
     * @Route("/set-amount", name="set_amount")
     */
    public function setAmountAction(Request $request)
    {
        $sum = $request->get('amount');

        $session = new Session();

        $session->set('sum', $sum);

        return $this->json([
            'success' => true
        ]);
    }


    /**
     * @Route("/qr-info", name="qr_code_page")
     */
    public function qrCodeAction(Request $request)
    {
        $session = new Session();

        $sellerInfo = [
            'shop' => $session->get('shop'),
            'bank' => $session->get('bank'),
            'sum' => $session->get('sum'),
            'currency' => $session->get('currency'),
            'iban' => $session->get('iban')
        ];

        return $this->render('@AppBundle/main/qr-code.html.twig', [
            'paymentInfo' => $sellerInfo
        ]);
    }

    /**
     * @Route("/select-account", name="select_account_page")
     */
    public function selectAccountAction(Request $request)
    {
        $client = new Client();

        $authorization = $client->post('https://test.api.ob.baltics.sebgroup.com/v1/user/authorization?bic=CBVILT2X', [
            'json' => [
                'ibsUserId' => 'ibsUser2'
            ],
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJERU1PRUUsaWJzVXNlcjEiLCJleHAiOjE1NTI5MDUzNjF9.LGAvX4M08dJVmbf8l5YFYSDMf-pj0mrbGv156Rx-e2wLlFXuSIbMN4Kej_wgEqAO5Br0aaEubvHDpWmTO4MHow'
            ],
            'verify' => false
        ]);

        $body = $authorization->getBody();

        $body = json_decode($body);

        $authorizationKey = $body->authorizationKey;

        $token = $client->post('https://test.api.ob.baltics.sebgroup.com/v1/user/authorization/' . $authorizationKey . '/token', [
            'json' => [
                'ibsUserId' => 'ibsUser2',
                'period' => 'month'
            ],
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJERU1PRUUsaWJzVXNlcjEiLCJleHAiOjE1NTI5MDUzNjF9.LGAvX4M08dJVmbf8l5YFYSDMf-pj0mrbGv156Rx-e2wLlFXuSIbMN4Kej_wgEqAO5Br0aaEubvHDpWmTO4MHow'
            ],
            'verify' => false
        ]);

        $token = json_decode($token->getBody()->getContents());

        $apiToken = $token->token;

        $session = new Session();

        $sellerInfo = [
            'shop' => $session->get('shop'),
            'bank' => $session->get('bank'),
            'sum' => $session->get('sum'),
            'currency' => $session->get('currency'),
            'iban' => $session->get('iban')
        ];

        $response = $client->get('https://test.api.ob.baltics.sebgroup.com/v1/bics/CBVILT2X/accounts', [
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => $apiToken
            ],
            'verify' => false
        ]);

        $body = $response->getBody();

        $body = json_decode($body);

        $bankAccounts = [];

        foreach ($body as $account) {
            $bankAccounts[] = [
                'bank' => 'SEB ' . substr($account->iban, 0, 2),
                'account' => $account->iban,
                'currency' => $account->currency,
                'sum' => $account->balance
            ];
        }

        return $this->render('@AppBundle/main/select-account.html.twig', [
            'accounts' => $bankAccounts,
            'paymentInfo' => $sellerInfo
        ]);
    }

    /**
     * @Route("/select-second-account", name="select_second_account_page")
     */
    public function selectSecondAccountAction(Request $request)
    {
        $sellerInfo = [
            'shop' => 'JSC Big Seller',
            'bank' => 'SEB',
            'sum' => 500,
            'currency' => 'EUR',
            'iban' => '**** **** **** 1234'
        ];

        $bankAccounts = [
            [
                'bank' => 'SEB',
                'account' => 'LT12345678910111213',
                'currency' => 'EUR',
                'sum' => '800'
            ],
            [
                'bank' => 'SEB',
                'account' => 'LT1234567891014151617',
                'currency' => 'EUR',
                'sum' => '310'
            ],
        ];

        return $this->render('@AppBundle/main/select-second-account.html.twig', [
            'accounts' => $bankAccounts,
            'paymentInfo' => $sellerInfo
        ]);
    }


    /**
     * @Route("/confirm-payment-page", name="confirm_payment_page")
     */
    public function confirmPaymentPageAction(Request $request)
    {
        $client = new Client();

        $authorization = $client->post('https://test.api.ob.baltics.sebgroup.com/v1/user/authorization?bic=CBVILT2X', [
            'json' => [
                'ibsUserId' => 'ibsUser2'
            ],
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJERU1PRUUsaWJzVXNlcjEiLCJleHAiOjE1NTI5MDUzNjF9.LGAvX4M08dJVmbf8l5YFYSDMf-pj0mrbGv156Rx-e2wLlFXuSIbMN4Kej_wgEqAO5Br0aaEubvHDpWmTO4MHow'
            ],
            'verify' => false
        ]);

        $body = $authorization->getBody();

        $body = json_decode($body);

        $authorizationKey = $body->authorizationKey;

        $token = $client->post('https://test.api.ob.baltics.sebgroup.com/v1/user/authorization/' . $authorizationKey . '/token', [
            'json' => [
                'ibsUserId' => 'ibsUser2',
                'period' => 'month'
            ],
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJERU1PRUUsaWJzVXNlcjEiLCJleHAiOjE1NTI5MDUzNjF9.LGAvX4M08dJVmbf8l5YFYSDMf-pj0mrbGv156Rx-e2wLlFXuSIbMN4Kej_wgEqAO5Br0aaEubvHDpWmTO4MHow'
            ],
            'verify' => false
        ]);

        $token = json_decode($token->getBody()->getContents());

        $apiToken = $token->token;

        $session = new Session();

        $sellerInfo = [
            'shop' => $session->get('shop'),
            'bank' => $session->get('bank'),
            'sum' => $session->get('sum'),
            'currency' => $session->get('currency'),
            'iban' => $session->get('iban')
        ];

        $response = $client->get('https://test.api.ob.baltics.sebgroup.com/v1/bics/CBVILT2X/accounts', [
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => $apiToken
            ],
            'verify' => false
        ]);

        $response = $client->post('https://test.api.ob.baltics.sebgroup.com/v1/bics/CBVILT2X/accounts/openaccount', [
            'headers' => [
                'Tpp-Token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnZWRpbWluYXMuaXZAZ21haWwuY29tLVRQUFRPS0VOLTEiLCJleHAiOjE1NTI5MDUzNjF9.chIk_mTB3iTWNLbhNbpIJ0p9oK3EYfVKSFm5uuax7srTSU-UbbULl64nl3z45cA01mxIEMxsC4t_oNYRlgHKwQ',
                'User-Token' => $apiToken
            ],
            'json' => [

            ],
            'verify' => false
        ]);


        $body = $response->getBody();

        $body = json_decode($body);

        return $this->render('@AppBundle/main/payment-confirmation.html.twig');
    }

    /**
     * @Route("/confirm-payment-screen", name="confirm_payment_screen")
     */
    public function confirmPaymentScreenAction(Request $request)
    {
        return $this->render('@AppBundle/main/confirm-payment-screen.html.twig');
    }

    /**
     * @Route("/payment-success", name="payment_success_page")
     */
    public function paymentSuccessAction(Request $request)
    {
        return $this->render('@AppBundle/main/payment-success.html.twig');
    }
}
