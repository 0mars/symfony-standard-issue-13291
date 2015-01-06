<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;

class DefaultController extends Controller
{
    /**
     * @Route("/app/original-not-working", name="original_not_working")
     */
    public function orginalNotWorkingAction()
    {
        $form = $this->createFileForm();

        if ($this->getRequest()->getMethod() === "POST") {
            $form->submit($this->getRequest()->request->all());
            var_dump($form->get('my_file')->getData());
        }

        return $this->render('default/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("app/original-working", name="original_working")
     */
    public function orginalWorkingAction()
    {
        $form = $this->createFileForm();

        if ($this->getRequest()->getMethod() === "POST") {
            $form->submit($this->getRequest()->request->get($form->getName()));
            var_dump($form->get('my_file')->getData());
        }

        return $this->render('default/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/app/expected-behaviour", name="expected_behaviour")
     */
    public function expectedBehaviourAction()
    {
        $form = $this->createFileForm();

        if ($this->getRequest()->getMethod() === "POST") {
            $form->submit(
                array_merge(
                    $this->getRequest()->request->get($form->getName()),
                    $this->getRequest()->files->get($form->getName())
                )
            );

            var_dump($form->get('my_file')->getData());
        }

        return $this->render('default/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("app/recommendation", name="recommendation")
     */
    public function recommendationAction(Request $request)
    {
        $form = $this->createFileForm();

        if ($request->getMethod() === "POST") {
            $form->handleRequest($request);
            var_dump($form->get('my_file')->getData());
        }

        return $this->render('default/index.html.twig', ['form' => $form->createView()]);
    }

    private function createFileForm()
    {
        return $this->createFormBuilder()
            ->add('my_file', 'file', [
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF',
                    ])
                ]
            ])
            ->add('submit', 'submit')
            ->getForm();
    }
}
