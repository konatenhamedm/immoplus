<?php

namespace App\Repository;

use App\Entity\Factureloc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Factureloc>
 *
 * @method Factureloc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factureloc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factureloc[]    findAll()
 * @method Factureloc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturelocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Factureloc::class);
    }

    public function save(Factureloc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Factureloc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Factureloc[] Returns an array of Factureloc objects
     */
    public function findAllFactureLocataire($value): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.locataire', 'l')
            ->andWhere('l.id = :id')
            ->andWhere('f.statut = :statut')
            ->setParameter('id', $value)
            ->setParameter('statut', 'impayer')
            ->orderBy('f.DateEmission', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Factureloc[] Returns an array of Factureloc objects
     */
    public function findAllFactureLocataireByAgentCampagne($agent, $campagne): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.locataire', 'l')
            ->innerJoin('f.compagne', 'c')
            ->innerJoin('f.appartement', 'a')
            ->innerJoin('a.maisson', 'm')
            ->innerJoin('m.IdAgent', 'ag')
            ->andWhere('f.compagne = :campagne')
            ->andWhere('ag.id = :agent')
            /* ->andWhere('f.statut = :statut') */
            ->setParameter('agent', $agent)
            ->setParameter('campagne', $campagne)
            /* ->setParameter('statut', 'payer') */
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Factureloc[] Returns an array of Factureloc objects
     */
    public function findAllFactureLocataireByAgentCampagneTotal($agent, $campagne): array
    {
        return $this->createQueryBuilder('f')
            ->select('sum(f.MntFact) - sum(f.SoldeFactLoc) encaisse,sum(f.SoldeFactLoc) reste')
            ->innerJoin('f.locataire', 'l')
            ->innerJoin('f.compagne', 'c')
            ->innerJoin('f.appartement', 'a')
            ->innerJoin('a.maisson', 'm')
            ->innerJoin('m.IdAgent', 'ag')
            ->andWhere('f.compagne = :campagne')
            ->andWhere('ag.id = :agent')
            /* ->andWhere('f.statut = :statut') */
            ->setParameter('agent', $agent)
            ->setParameter('campagne', $campagne)
            /* ->setParameter('statut', 'payer') */
            ->getQuery()
            ->getResult();
    }



    public function findAllFactureCampagne($value)
    {
        return $this->createQueryBuilder('f')
            ->select('SUM(f.SoldeFactLoc) as somme')
            ->innerJoin('f.compagne', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllFactureCampagneImprime($value)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.compagne', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Factureloc[] Returns an array of Factureloc objects
     */
    public function findAllFactureLocataireImpayer($value): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.LibFacture', 'f.SoldeFactLoc', 'f.DateLimite')
            ->innerJoin('f.locataire', 'l')
            ->andWhere('l.id = :id')
            ->andWhere('f.statut = :statut')
            ->setParameter('id', $value)
            ->setParameter('statut', 'impayer')
            /* ->groupBy('f.LibFacture', 'f.DateLimite') */
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Factureloc
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
