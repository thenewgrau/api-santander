<?php

namespace App\Controller;

use App\Dto\TransacaoRealizarDto;
use App\Entity\Transacao;
use App\Repository\ContaRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TransacoesController extends AbstractController
{
    #[Route('/transacoes', name: 'transacoes_realizar', methods: ['POST']),]
    public function realizar(
        #[MapRequestPayload(acceptFormat: 'json')]
        TransacaoRealizarDto $entrada,

        ContaRepository $contaRepository,
        EntityManagerInterface $entityManager
        
    ): Response | JsonResponse
    {

        // 1. Validar se existe ID origem / ID destino / valor

        $erros = [];

        // Validar as DTO de entrada 

        // Validar se há valor para o id do usuario de origem
        if(empty($entrada->getIdUsuarioOrigem())) {
            $erros[] = ['message' => 'O ID de Origem é obrigatório'];
        }
        
        // Validar se há valor para o id do usuario de destino
        if(empty($entrada->getIdUsuarioDestino())) {
            $erros[] = ['message' => 'O ID de Destino é obrigatório'];
        }

        // Validar se há valor de saldo do usuario
        if(empty($entrada->getValor())) {
            $erros[] = ['message' => 'O valor deve ser informado'];
        }

        // Validar se o valor do usuario é maior que zero
        if((float) $entrada->getValor() <= 0) {
            $erros[] = ['message' => 'Valor deve ser maior que zero!'];
        }

        // Validar se os usuarios de destino e origem sao distintos
        if($entrada->getIdUsuarioOrigem() === $entrada->getIdUsuarioDestino()) {
            $erros[] = ['message' => 'As contas devem ser destintas'];
        }
        
        if(count($erros) > 0) {
            return $this->json($erros, 422);
        }

        // 2. Validar se contas existem

        $usuarioOrigemExistente = $contaRepository->findByUsuarioId($entrada->getIdUsuarioOrigem());
        if ($usuarioOrigemExistente ==  null){
            return $this->json([
                'message' => 'O ID do usuário origem informado não existe'
            ], 409);
        }
        
        $usuarioDestinoExistente = $contaRepository->findByUsuarioId($entrada->getIdUsuarioDestino());
        if ($usuarioDestinoExistente == null){
            return $this->json([
                'message' => 'O ID do usuário destino informado não existe'
            ], 409);
        }

        // 3. Validar se a origem possui saldo suficiente
        
        if ( (float) $usuarioOrigemExistente->getSaldo() < (float) $entrada->getValor()) {
            return $this->json([
            'message' => 'Saldo insuficiente na conta de origem'
            ], 409);
        }

        $saldo = (float) $usuarioOrigemExistente->getSaldo();
        $valorT = (float) $entrada->getValor();
        $saldoDestino = (float) $usuarioDestinoExistente->getSaldo();

        $usuarioOrigemExistente->setSaldo($saldo - $valorT);
        $entityManager->persist($usuarioOrigemExistente);

        $usuarioDestinoExistente->setSaldo($valorT + $saldoDestino);
        $entityManager->persist($usuarioOrigemExistente);

        $transacao = new Transacao();
        $transacao->setDataHora(new DateTime());
        $transacao->setValor($entrada->getValor());
        $transacao->setContaOrigem($usuarioOrigemExistente);
        $transacao->setContaDestino($usuarioDestinoExistente);
        $entityManager->persist($transacao);
        $entityManager->flush();

        return new Response(status: 204);
    }
}
