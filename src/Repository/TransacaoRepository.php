<?php

namespace App\Repository;

use App\Entity\Transacao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transacao>
 *
 * @method Transacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transacao[]    findAll()
 * @method Transacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacao::class);
    }

    /**
     * @return Transacao[]
     */
    public function findByContaId(int $id): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.contaOrigem = :id OR t.contaDestino = :id')
            ->setParameter('id', $id)
            ->orderBy('t.dataHora', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    // /**
    //  * @return Transacao[] Returns an array of Transacao objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('t')
    //         ->andWhere('t.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('t.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findOneBySomeField($value): ?Transacao
    // {
    //     return $this->createQueryBuilder('t')
    //         ->andWhere('t.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}
