<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/qr-info", name="qr_code_page")
     */
    public function qrCodeAction(Request $request)
    {
        $sellerInfo = [
            'shop' => 'JSC Big Seller',
            'bank' => 'SEB',
            'sum' => 500,
            'currency' => 'EUR',
            'iban' => '**** **** **** 1234'
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
        return $this->render('@AppBundle/main/payment-confirmation.html.twig');
    }

    /**
     * @Route("/payment-success", name="payment_success_page")
     */
    public function paymentSuccessAction(Request $request)
    {
        return $this->render('@AppBundle/main/payment-success.html.twig');
    }
}
