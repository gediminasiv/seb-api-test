<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Request as RequestInfo;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/fetch", name="add_request")
     */
    public function addRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ipAddress = $request->request->get('ip');
        $headers = get_object_vars(json_decode($request->request->get('headers')));
        $fromPage = $request->request->get('fromPage');

        $requestData = [];

        foreach ($headers as $key => $header) {
            $requestData[] = $key . ':' . $header;
        }

        $requestInfo = new RequestInfo();

        $requestInfo->setIpAddress($ipAddress);
        $requestInfo->setFromPage($fromPage);
        $requestInfo->setRequestTime(new \DateTime());
        $requestInfo->setRequestInfo($requestData);

        $em->persist($requestInfo);

        $em->flush();

        return $this->json([
            'success' => true
        ]);
    }

}
