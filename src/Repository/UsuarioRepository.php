<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

        /**
         * @return Usuario[] Returns an array of Usuario objects
         */

    //  Retorna um objeto de Usuario
        public function findByCpf($cpf): Usuario|null
        {
            return $this->createQueryBuilder('u')
                ->where('u.cpf = :cpf')
                ->setParameter('cpf', $cpf)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Usuario
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
