<?php

namespace App\Controller;

use App\Entity\Transacao;
use App\Repository\TransacaoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use transacaoDto;

#[Route('/api')]
final class TransacaoController extends AbstractController
{
    #[Route('/transacoes',name: 'app_transacao', methods:['GET'])]
    public function transacao(
        #[MapRequestPayload(acceptFormat: 'json')]

        TransacaoDto $transacaoDto,
        
        EntityManagerInterface $entityManager,
        TransacaoRepository $transacaoRepository

    ): JsonResponse
    {



        return $this->json([]);
    }
}
