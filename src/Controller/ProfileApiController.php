<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\CompanyRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/profile')]
class ProfileApiController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(ProfileRepository $repo): JsonResponse
    {
        $data = [];
        foreach ($repo->findAll() as $item) {
            $data[] = $item->toData();
        }
        return new JsonResponse($data);
    }

    private function requestToEntity(Request $request, CompanyRepository $companyRepository, ?Profile $entity): Profile|Response
    {
        try {
            $json = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'invalid JSON input: ' . $e->getMessage()], 400);
        }
        foreach (['name', 'surname', 'company'] as $field) {
            if (!isset($json->$field)) {
                return new JsonResponse(['error' => $field . ' should not be null'], 400);
            }
        }

        if ($entity === null) {
            $entity = new Profile();
        }
        $company = $companyRepository->find((int)$json->company);
        if ($company === null) {
            return new JsonResponse(['error' => 'Company does not exist'], 404);
        }
        $entity->setCompany($company);
        $entity->setName(trim($json->name));
        $entity->setSurname(trim($json->surname));

        if (isset($json->phone)) {
            $entity->setPhone(trim($json->phone));
        }

        return $entity;
    }

    #[Route('/', methods: ['POST'])]
    public function new(Request $request, CompanyRepository $companyRepository, ProfileRepository $profileRepository): Response
    {
        $entityOrResponse = $this->requestToEntity($request, $companyRepository, null);
        if ($entityOrResponse instanceof Response) {
            return $entityOrResponse;
        }

        $profileRepository->add($entityOrResponse, true);
        return new Response();
    }

    #[Route('/{profile}', methods: ['PUT'])]
    public function put(Request $request, CompanyRepository $companyRepository, ProfileRepository $profileRepository,
                        Profile $profile = null): Response
    {
        if ($profile === null) {
            return new JsonResponse(['error' => 'Profile does not exist'], 404);
        }

        $entityOrResponse = $this->requestToEntity($request, $companyRepository, $profile);
        if ($entityOrResponse instanceof Response) {
            return $entityOrResponse;
        }
        $profileRepository->add($entityOrResponse, true);

        return new Response();
    }

    #[Route('/{profile}', methods: ['DELETE'])]
    public function delete(ProfileRepository $profileRepository, Profile $profile = null): Response
    {
        if ($profile === null) {
            return new JsonResponse(['error' => 'Profile does not exist'], 404);
        }

        $profileRepository->remove($profile, true);
        return new Response();
    }
}