<?php

namespace App\Repository;

use App\Entity\Conta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conta>
 */
class ContaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conta::class);
    }

    //    /**
    //     * @return Conta[] Returns an array of Conta objects
    //     */
       public function findByUsuarioId($usuarioId): Conta|null
       {
           return $this->createQueryBuilder('c')
                ->join('c.usuario', 'u')
                ->where('u.id = :id')
                ->setParameter('id', $usuarioId)
                ->getQuery()
                ->getOneOrNullResult()
           ;
       }

       public function findByContaId($contaId): Conta|null
       {
           return $this->createQueryBuilder('c')
                ->where('c.id = :id')
                ->setParameter('id', $contaId)
                ->getQuery()
                ->getOneOrNullResult()
           ;
       }

       public function debitar(Conta $conta, float $valor): void
        {
            $this->createQueryBuilder('c')
                ->update()
                ->set('c.saldo', 'c.saldo - :val')
                ->andWhere('c.id = :id')
                ->setParameter('val', $valor)
                ->setParameter('id', $conta->getId())
                ->getQuery()
                ->execute()
            ;
        }

        public function depositar(Conta $conta, float $valor): void
        {
            $this->createQueryBuilder('c')
                ->update()
                ->set('c.saldo', 'c.saldo + :val')
                ->andWhere('c.id = :id')
                ->setParameter('val', $valor)
                ->setParameter('id', $conta->getId())
                ->getQuery()
                ->execute()
            ;
        }

        public function findByFiltro(string $valor) {

            $q = $this->createQueryBuilder('c');

            return $q
                ->join('c.usuario', 'u')
                ->where(
                    $q->expr()->like('u.nome', ':valor')
                )
                ->orWhere(
                    $q->expr()->like('u.email', ':valor')
                )
                ->orWhere(
                    $q->expr()->like('u.cpf', ':valor')
                )
                ->orWhere(
                    $q->expr()->like('u.telefone', ':valor')
                )
                ->orWhere(
                    $q->expr()->like('c.numero', ':valor')
                )
                ->setParameter('valor', "%$valor%")
                ->getQuery()
                ->getResult();
                
            ;

        }
    //    /**
    //     * @return Conta[] Returns an array of Conta objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conta
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
