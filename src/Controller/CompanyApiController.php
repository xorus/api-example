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

#[Route('/api/company')]
class CompanyApiController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(CompanyRepository $repo): JsonResponse
    {
        $data = [];
        foreach ($repo->findAll() as $item) {
            $data[] = $item->toData();
        }
        return new JsonResponse($data);
    }
}