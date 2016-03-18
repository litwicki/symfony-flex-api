<?php namespace Tavro\Bundle\CoreBundle\Services\Repository;

use Doctrine\ORM\EntityRepository;

class TavroRepository extends EntityRepository
{

    /**
     * @param array $organizations
     * @param $size
     * @param $offset
     * @param array $params
     *
     * @return mixed
     */
    public function findAllByOrganization(array $organizations, $size, $offset, array $params = array())
    {

        $sortOrder = isset($params['sortOrder']) ? $params['sortOrder'] : 'DESC';
        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : 'id';

        unset($params['orderBy']);
        unset($params['sortOrder']);

        //append x. so we know what object we're sorting by..
        $orderBy = sprintf('x.%s', $orderBy);

        $q = $this->getEntityManager()->createQueryBuilder();
        $q->select('x')->from($this->getClassName(), 'x')
            ->where('x.organization IN(:organizations)')
            ->setParameter('organizations', array_keys($organizations))
            ->orderBy($orderBy, $sortOrder)
            ->setMaxResults($size)
            ->setFirstResult($offset);

        if(!empty($params)) {
            foreach($params as $param => $value) {
                $q->andWhere(sprintf('x.%s = :%s', $param, $param));
                $q->setParameter($param, $value);
            }
        }

        $query = $q->getQuery();

        $result = $query->getResult();

        return $result;

    }

}