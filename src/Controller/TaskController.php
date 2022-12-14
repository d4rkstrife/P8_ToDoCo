<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private readonly TaskRepository $taskRepository, private EntityManagerInterface $em){

    }

    #[Route('/tasks', name: 'task_list')]
    public function listAction(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        };
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $this->taskRepository->findAll()]
        );
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        };
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(date_create());
            $task->setIsDone(false);
            $task->setUser($this->getUser());
            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        };
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        };
        $task->setIsDone(!$task->isIsDone());
        $this->em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        };
        if($task->getUser()===$this->getUser()){
            $this->em->remove($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else if ($task->getUser()!==$this->getUser()){
            $this->addFlash('error', "Vous n'êtes pas autorisé à effacer cette tâche.");
        }


        return $this->redirectToRoute('task_list');
    }
}
