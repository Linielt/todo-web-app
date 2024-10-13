<?php

namespace App\Controller;

use App\Entity\TodoItem;
use App\Form\TodoItemFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $todoItems = $entityManager->getRepository(TodoItem::class)->findAll();

        return $this->render('todo/index.html.twig', [
            'todoItems' => $todoItems,
        ]);
    }

    #[Route('/todo', name: 'create_todo')]
    public function createTodo(EntityManagerInterface $entityManager, Request $request): Response
    {
        $todoItem = new TodoItem();
        $todoItem->setTitle('');
        $todoItem->setDescription('');
        $todoItem->setDueDate(new \DateTimeImmutable('now'));

        $form = $this->createForm(TodoItemFormType::class, $todoItem);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $todoItem = $form->getData();

            $entityManager->persist($todoItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_index');
        }

        return $this->render('todo/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/todo/{id}', name: 'app_todo_deletetodo', methods: ['GET', 'DELETE'])]
    public function deleteTodo(EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $todoItemToDelete = $entityManager->getRepository(TodoItem::class)->find($id);

        if (!$todoItemToDelete)
        {
            throw $this->createNotFoundException("Could not find todo with id: " . $id);
        }

        $entityManager->remove($todoItemToDelete);
        $entityManager->flush();
        return $this->redirectToRoute('app_todo_index');
    }

    #[Route('/todo/update/{id}', name: 'app_todo_updatetodo')]
    public function updateTodo(EntityManagerInterface $entityManager, Request $request, int $id)
    {
        $todoItemToUpdate = $entityManager->getRepository(TodoItem::class)->find($id);

        if (!$todoItemToUpdate)
        {
            throw $this->createNotFoundException("Could not find todo with id " . $id);
        }

        $form = $this->createForm(TodoItemFormType::class, $todoItemToUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $todoItemToUpdate = $form->getData();

            $entityManager->persist($todoItemToUpdate);
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_index');
        }

        return $this->render('todo/form.html.twig', [
            'form' => $form,
        ]);
    }
}
