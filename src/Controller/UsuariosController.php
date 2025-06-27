<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Entity\Usuario;
use App\Repository\ContaRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use UsuarioContaDto;
use UsuarioDto;

#[Route('/api')]
final class UsuariosController extends AbstractController
{
    #[Route('/usuarios', name: 'usuarios_criar', methods: ['POST'])]
    public function criar(
        #[MapRequestPayload(acceptFormat: 'json')]
        UsuarioDto $usuarioDto,

        EntityManagerInterface $entityManager,
        UsuarioRepository $usuarioRepository

    ): JsonResponse
    {
        // dd($usuarioDto);
        $erros = [];
        if (empty($usuarioDto->getNome())) {
            $erros[] = ['message' => 'O nome é obrigatório!'];
        }
        if (empty($usuarioDto->getEmail())) {
            $erros[] = ['message' => 'O email é obrigatório!'];
        }
        if (empty($usuarioDto->getSenha())) {
            $erros[] = ['message' => 'A senha é obrigatória!'];
        }
        if (empty($usuarioDto->getTelefone())) {
            $erros[] = ['message' => 'O telefone é obrigatório!'];
        }
        if (empty($usuarioDto->getCpf())) {
            $erros[] = ['message' => 'O CPF é obrigatório!'];
        }
        if (count($erros) > 0){
            return $this->json($erros, 422); 
        }


        // converte o DTO para a entidade Usuario
        $usuario = new Usuario();
        $usuario->setNome($usuarioDto->getNome());
        $usuario->setEmail($usuarioDto->getEmail());
        //$usuario->setSenha(password_hash($usuarioDto->getSenha(), PASSWORD_BCRYPT));

        $usuario->setSenha($usuarioDto->getSenha());
        $usuario->setTelefone($usuarioDto->getTelefone());
        $usuario->setCpf($usuarioDto->getCpf());

        $usuarioExistente = $usuarioRepository->findByCpf($usuarioDto->getCpf());
        if ($usuarioExistente){
            return $this->json([
                'message' => 'O CPF informado já está cadastrado'
            ], 409);
        }

        // cria o registro no banco de dados
        
        $entityManager->persist($usuario);

        // instanciar o objeto Conta

        $conta = new Conta();
        $conta->setNumero(preg_replace('/\D/','', uniqid()));
        $conta->setSaldo('0');
        $conta->setUsuario($usuario);

        // cria registro na tb na conta

        $entityManager->persist($conta);
        $entityManager->flush();

        // retornar os dados de usuario e conta

        $usuarioContaDto = new UsuarioContaDto();
        $usuarioContaDto->setId($usuario->getId());
        $usuarioContaDto->setNome($usuario->getNome());
        $usuarioContaDto->setCpf($usuario->getCpf());
        $usuarioContaDto->setEmail($usuario->getEmail());
        $usuarioContaDto->setTelefone($usuario->getTelefone());
        $usuarioContaDto->setNumeroConta($conta->getNumero());
        $usuarioContaDto->setSaldo($conta->getSaldo());

        return $this->json($usuarioContaDto, status:201);
    }

    #[Route('/usuarios/{id}', name: 'usuarios_buscar', methods: ['GET'])]
    public function buscarPorId(
        int $id,
        ContaRepository $contaRepository


    ){

       dd($contaRepository->findByUsuarioId($id));

        if (!$conta){
        return $this->json([
            'message'=> 'Usuário não encontrado'
        ], status: 404);
        }

        $usuarioContaDto = new UsuarioContaDto();
        $usuarioContaDto->setId($conta->getUsuario()->getId());
        $usuarioContaDto->setNome($conta->getUsuario()->getNome());
        $usuarioContaDto->setCpf($conta->getUsuario()->getCpf());
        $usuarioContaDto->setEmail($conta->getUsuario()->getEmail());
        $usuarioContaDto->setTelefone($conta->getUsuario()->getTelefone());
        $usuarioContaDto->setNumeroConta($conta->getNumero());
        $usuarioContaDto->setSaldo($conta->getSaldo());

        return $usuarioContaDto;
    }

    
}
