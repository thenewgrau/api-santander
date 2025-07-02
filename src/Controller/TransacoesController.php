<?php

namespace App\Controller;

use App\Dto\TransacaoRealizarDto;
use App\Repository\ContaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TransacoesController extends AbstractController
{
    #[Route('/transacoes', name: 'transacoes_realizar', methods: ['POST']),]
    public function realizar(
        #[MapRequestPayload(acceptFormat: 'json')]
        TransacaoRealizarDto $entrada,

        ContaRepository $contaRepository
        
    ): JsonResponse
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
        

        

        return $this->json([
            $entrada, 
            $usuarioDestinoExistente,
            $usuarioOrigemExistente

        ]);
    }
}
