<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
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

        //append n. so we know what object we're sorting by..
        $orderBy = sprintf('n.%s', $orderBy);

        $q = $this->getEntityManager()->createQueryBuilder();
        $q->select('n')->from('TavroCoreBundle:Node', 'n')
            ->orderBy($orderBy, $sortOrder)
            ->setMaxResults($size)
            ->setFirstResult($offset);

        if(!empty($params)) {
            foreach($params as $param => $value) {
                $q->andWhere(sprintf('n.%s = :%s', $param, $param));
                $q->setParameter($param, $value);
            }
        }

        $query = $q->getQuery();

        $result = $query->getResult();

        return $result;

    }

}