<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\Type\PlayerType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/players/add", name="add-new-player", methods={"POST"})
     *
     * @param Request $request
     *
     * @return \Exception | JsonResponse
     */
    public function addNewPlayer(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        /** @var Player $player */
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player);

        $form->submit($data);
        if ($form->isValid()) {
            $player        = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $entityManager->persist($player);
                $entityManager->flush();
            } catch (\Exception $exception) {
                /**
                 * $this->handleException($exception);   <== log the exception so that a dev can look into it
                 *                                 and return a message to the user that an error occurred
                 */
                return $exception;
            }

            return $this->json([
                'message' => 'The player has been added successfully'
            ]);
        } else {

            return $this->json([
                'errors' => $this->getErrorsFromForm($form)
            ]);
        }
    }

    /**
     * @Route("/players/list", name="list-all-players")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     * @return JsonResponse
     */
    public function listAllPlayers(Request $request, PaginatorInterface $paginator)
    {
        return $this->returnPaginatedPlayersList($request, $paginator);
    }

    /**
     * @Route("/players/list/{country}", name="list-all-players-by-country", requirements={"country"="^[^\s]+[a-zA-Z\s]+"})
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param $country
     *
     * @return JsonResponse
     */
    public function listAllPlayersByCountry(Request $request, PaginatorInterface $paginator, $country = false)
    {
        $country = trim($country);

        return $this->returnPaginatedPlayersList($request, $paginator, $country);
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param bool $country
     *
     * @return JsonResponse
     */
    private function returnPaginatedPlayersList(Request $request, PaginatorInterface $paginator, $country = false)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var \App\Repository\Player $playerRepo */
        $playerRepo = $entityManager->getRepository(Player::class);
        $page       = $request->query->getInt('page') ? $request->query->getInt('page') : 1;  //make sure 0 has default value of 1
        $size       = $request->query->getInt('size') ? $request->query->getInt('size') : 10; //make sure 0 has default value of 1

        if ($country) {
            $queryBuilder = $playerRepo->findAllPlayersByCountryQB($country);
        } else {
            $queryBuilder = $playerRepo->findAllPlayersQB();
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            $size
        );

        return $this->json([
            'items'        => $pagination->getItems(),
            'totalItems'   => $pagination->getTotalItemCount(),
            'currentPage'  => $pagination->getCurrentPageNumber(),
            'itemsPerPage' => $pagination->getItemNumberPerPage()
        ]);
    }

    /**
     * Recursive function that goes throughout the whole form tree, collecting error messages from all children
     *
     * @param FormInterface $form
     *
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                $childErrors = $this->getErrorsFromForm($childForm);

                if ($childErrors) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}