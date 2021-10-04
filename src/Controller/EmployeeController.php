<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/employee")
 */
class EmployeeController extends AbstractController
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->encoder = $hasher;
    }
    /**
     * @Route("/", name="employee_index", methods={"GET"})
     */
    public function index(EmployeeRepository $employeeRepository): Response
    {
        if($this->getUser()){
            return $this->render('employee/index.html.twig', [
                'employees' => $employeeRepository->findAll(),
            ]);
        }else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/new", name="employee_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        if(in_array('ROLE_RH', $this->getUser()->getRoles())){
            $employee = new Employee();
            $form = $this->createForm(EmployeeType::class, $employee);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    
                $image = $form->get('employeePhoto')->getData();
                if($image) {
                    $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFileName);
                    $newFileName = $safeFileName.'-'.uniqid().'-'.$image->guessExtension();
    
                    try {
                        $image->move(
                            $this->getParameter('image_directory'),
                            $newFileName
                        );
                    } catch (FileException $e) {
                       
                    }
    
                    $employee->setEmployeePhoto($newFileName);
                }
    
                $employeePassword = $employee->getPassword();
    
                $employee->setPassword($this->encoder->hashPassword($employee, $employeePassword));

                if($employee->getSector() == 'RH'){
                    $employee->setRoles(['ROLE_RH']);
                }
                if($employee->getSector() == 'Informatique'){
                    $employee->setRoles(['ROLE_INFO']);
                }
                if($employee->getSector() == 'Comptabilité'){
                    $employee->setRoles(['ROLE_COMPTA']);
                }
                if($employee->getSector() == 'Direction'){
                    $employee->setRoles(['ROLE_DIRECTION']);
                }
    
    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($employee);
                $entityManager->flush();

                return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('employee/new.html.twig', [
                'employee' => $employee,
                'form' => $form,
                'errors' => $form->getErrors()
            ]);
        }
        return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="employee_show", methods={"GET"})
     */
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="employee_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Employee $employee): Response
    {
        if(in_array('ROLE_RH', $this->getUser()->getRoles())){
            $form = $this->createForm(EmployeeType::class, $employee);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('employee/edit.html.twig', [
                'employee' => $employee,
                'form' => $form,
            ]);
        }
        return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="employee_delete", methods={"POST"})
     */
    public function delete(Request $request, Employee $employee): Response
    {
        if(in_array('ROLE_RH', $this->getUser()->getRoles())){

            if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($employee);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
