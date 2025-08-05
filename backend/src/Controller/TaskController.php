<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'get_tasks', methods: ['GET'])]
    public function getAllTasks(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAll();

        return $this->json($tasks, 200, [], ['groups' => 'task:read']);

    }


    #[Route('/tasks', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? '');
        $task->setStatus($data['status'] ?? 'pending');
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json($task, 201, [], ['groups' => 'task:read']);
    }

    #[Route('/tasks/{id}', name: 'get_task', methods: ['GET'])]
    public function getTask(?Task $task): JsonResponse
    {
        if (!$task) {
            return $this->json(['message' => 'Tâche non trouvée'], 404);
        }

        return $this->json($task, 200, [], ['groups' => 'task:read']);
    }

    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id,Request $request,EntityManagerInterface $entityManager,TaskRepository $taskRepository): JsonResponse 
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Tâche non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }

        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }

        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        $task->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        return $this->json($task, 200, [], ['groups' => 'task:read']);
    }

    #[Route('/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(int $id,TaskRepository $taskRepository,EntityManagerInterface $entityManager): JsonResponse 
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Tâche non trouvée'], 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json(['message' => 'Tâche supprimée avec succès'], 200);
    }



}
