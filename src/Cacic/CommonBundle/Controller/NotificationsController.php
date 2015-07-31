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
use Cacic\CommonBundle\Form\Type\NotificationsType;


class NotificationsController extends Controller
{
    /**
     * Lista de notificações para o usuário no formato JSON
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        $limit = $request->get('limit');
        $offset = $request->get('offset');

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

    /**
     * Lista de notificações
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // Mostra todas as mensagens não lidas
        $unread = $request->get('unread');
        $limit = 0;
        $offset = 0;

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

    /**
     * Visualiza notificação
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getNotificationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $idNotification = $request->get('idNotification');

        if (empty($idNotification)) {
            $notices = $this->get('session')->getFlashBag()->get('success', array());

            // Redireciona somente se nao for apos a ediçao
            if (empty($notices)) {
                throw $this->createNotFoundException("Notificação não encontrada");
            } else {
                return $this->redirect( $this->generateUrl( 'cacic_notifications_list') );
            }
        }

        $notification = $em->getRepository("CacicCommonBundle:Notifications")->find($idNotification);

        if (empty($notification)) {
            $notices = $this->get('session')->getFlashBag()->get('success', array());

            // Redireciona somente se nao for apos a ediçao
            if (empty($notices)) {
                throw $this->createNotFoundException("Notificação não encontrada");
            } else {
                return $this->redirect( $this->generateUrl( 'cacic_notifications_list') );
            }
        }

        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // Usuário só pode ver suas próprias notificações
            $user = $this->getUser();
            if ($user->getEmail() != $notification->getToAddr()) {
                throw $this->createAccessDeniedException();
            }
        }

        $form = $this->createForm(
            new NotificationsType(),
            $notification,
            array(
                'disabled' => true
            )
        );

        return $this->render(
            'CacicCommonBundle:Notifications:getNotification.html.twig',
            array(
                'notification' => $notification,
                'form' => $form->createView()
            )
        );

    }

    /**
     * Marcar notificaçao como lida
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $idNotification = $request->get('id');

        if (empty($idNotification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        $notification = $em->getRepository("CacicCommonBundle:Notifications")->find($idNotification);

        if (empty($notification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // Usuário só pode ver suas próprias notificações
            $user = $this->getUser();
            if ($user->getEmail() != $notification->getToAddr()) {
                $this->get('session')->getFlashBag()->add('error', 'Permissão negada!');

                throw $this->createAccessDeniedException();
            }
        }

        $notification->setReadDate(new \DateTime());
        $em->persist($notification);
        $em->flush();

        // Retorna JSON
        $response = new JsonResponse();
        $response->setStatusCode(200);

        $this->get('session')->getFlashBag()->add('success', 'Notificação marcada como lida com sucesso!');

        $response->setContent(json_encode(
            array(
                'status' => 'ok'
            ),
            true
        ));

        return $response;
    }

    /**
     * Excluir notificação
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $idNotification = $request->get('id');

        if (empty($idNotification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        $notification = $em->getRepository("CacicCommonBundle:Notifications")->find($idNotification);

        if (empty($notification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // Usuário só pode ver suas próprias notificações
            $user = $this->getUser();
            if ($user->getEmail() != $notification->getToAddr()) {
                $this->get('session')->getFlashBag()->add('error', 'Permissão negada!');

                throw $this->createAccessDeniedException();
            }
        }

        $em->remove($notification);
        $em->flush();

        // Retorna JSON
        $response = new JsonResponse();
        $response->setStatusCode(200);

        $this->get('session')->getFlashBag()->add('success', 'Notificação removida com sucesso!');

        $response->setContent(json_encode(
            array(
                'status' => 'ok'
            ),
            true
        ));

        return $response;
    }

    /**
     * Marca notificação como não lida
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unreadAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $idNotification = $request->get('id');

        if (empty($idNotification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        $notification = $em->getRepository("CacicCommonBundle:Notifications")->find($idNotification);

        if (empty($notification)) {
            $this->get('session')->getFlashBag()->add('error', 'Notificação não encontrada!');

            throw $this->createNotFoundException("Notificação não encontrada");
        }

        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // Usuário só pode ver suas próprias notificações
            $user = $this->getUser();
            if ($user->getEmail() != $notification->getToAddr()) {
                $this->get('session')->getFlashBag()->add('error', 'Permissão negada!');

                throw $this->createAccessDeniedException();
            }
        }

        $notification->setReadDate(null);
        $em->persist($notification);
        $em->flush();

        // Retorna JSON
        $response = new JsonResponse();
        $response->setStatusCode(200);

        $this->get('session')->getFlashBag()->add('success', 'Notificação marcada como não lida com sucesso!');

        $response->setContent(json_encode(
            array(
                'status' => 'ok'
            ),
            true
        ));

        return $response;
    }

}