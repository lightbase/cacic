<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 29/07/15
 * Time: 17:46
 */

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class NotificationsController extends Controller
{
    public function getAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        $limit = $request->get('limit');
        $offset = $request->get('offset');

        if (empty($limit)) {
            $limit = 10;
        }

        if (empty($offset)) {
            $offset = 0;
        }

        if($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // Nesse caso podem ser listadas todas as notificações
            $notifications = $em->getRepository("CacicCommonBundle:Notifications")->getNotifications(
                $limit,
                $offset,
                null,
                true // Filtra pelas não lidas
            );

        } else {
            $user = $request->getUser();

            // Pega somente as notificações atribuídas a esse usuário
            $notifications = $em->getRepository("CacicCommonBundle:Notifications")->getNotifications(
                $limit,
                $offset,
                $user->getEmail(), // Somente notificações enviadas para o usuário que está logado
                true // Filtra pelas não lidas
            );
        }

        // Serialize objects to JSON
        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($notifications, 'json');

        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setContent($jsonContent);

        return $response;
    }

    public function listAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $unread = $request->get('undread');
        if (empty($unread)) {
            $unread = true;
        } else {
            $unread = false;
        }

        if($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $notifications = $em->getRepository("CacicCommonBundle:Notifications")->getNotifications(
                $limit,
                $offset,
                null,
                $unread // Filtra pelas não lidas
            );
        } else {
            $user = $this->getUser();
            $notifications = $em->getRepository("CacicCommonBundle:Notifications")->getNotifications(
                $limit,
                $offset,
                $user->getEmail(), // Somente notificações enviadas para o usuário que está logado
                $unread // Filtra pelas não lidas
            );
        }

        return $this->render(
            'CacicCommonBundle:Notifications:list.html.twig',
            array(
                'notifications' => $notifications
            )
        );

    }

    public function getNotificationAction(Request $request) {

    }

    public function readAction(Request $request) {

    }

}