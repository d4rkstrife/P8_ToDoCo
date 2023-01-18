<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
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
        if(!$this->isGranted(UserVoter::VIEW)){
            return $this->redirectToRoute('app_login');
        };
        $userTasks = $this->taskRepository->findBy(["user" => $this->getUser(), "isDone" => false]);
        $anonymeTasks = $this->taskRepository->findBy(["user" => null, "isDone" => false]);
        $tasks = array_merge($userTasks, $anonymeTasks);
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $tasks]
        );
    }

    #[Route('/doneTasks', name: 'done_task_list')]
    public function doneListAction(): Response
    {
        if(!$this->isGranted(UserVoter::VIEW)){
            return $this->redirectToRoute('app_login');
        };
        $userTasks = $this->taskRepository->findBy(["user" => $this->getUser(), "isDone" => true]);
        $anonymeTasks = $this->taskRepository->findBy(["user" => null, "isDone" => true]);
        $tasks = array_merge($userTasks, $anonymeTasks);
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $tasks]
        );
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request): Response
    {
        if(!$this->isGranted(UserVoter::VIEW)){
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
        if(!$this->isGranted(UserVoter::VIEW)){
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
        if(!$this->isGranted(UserVoter::VIEW)){
            return $this->redirectToRoute('app_login');
        };
        $task->setIsDone(!$task->isIsDone());
        $this->em->flush();

        $this->addFlash('success', sprintf("L'avancement de la tâche a été modifié.", $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task)
    {
        if(!$this->isGranted(UserVoter::VIEW)){
            return $this->redirectToRoute('app_login');
        };
        if($this->isGranted(TaskVoter::DELETE, $task)){
            dd('tutu');
            $this->em->remove($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', "Vous n'êtes pas autorisé à effacer cette tâche.");
        }

        return $this->redirectToRoute('task_list');
    }
}
