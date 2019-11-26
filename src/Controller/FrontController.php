<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('App:Article')->findLastNews();

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            $mailer = $this->get('mailer');

            $message = (new \Swift_Message($contact->getSubject()))
                ->setFrom($contact->getEmail())
                ->setTo('leo.hugues@hotmail.fr')
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'email/contact.html.twig',
                        ['name' => $contact->getUsername()]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);
            $em->persist($contact);
            $em->flush();
        }

        return $this->render('front/index.html.twig', [
            'contact_form'  => $form->createView(),
            'articles'      => $articles,
        ]);
    }
}
