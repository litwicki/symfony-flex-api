<?php

namespace Tavro\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\User;

class UsersController extends Controller
{
    public function indexAction(Request $request)
    {

        $thisPage = $request->get('page', 1);
        $limit = $request->get('limit', 25);

        $repo = $this->getDoctrine()->getManager()->getRepository('TavroCoreBundle:User');
        $entities = $repo->getAll($thisPage, $limit);

        $total = $repo->getCountOfAll();

        $maxPages = ceil($total / $limit);

        $start = ($thisPage - 1) * $limit + 1;
        $end = $total;

        if ($limit < $total) {
            $end = $limit * $thisPage;
            if ($end > $total) {
                $end = $total;
            }
        }

        return $this->render('TavroAdminBundle:Users:index.html.twig', [
            'users' => $entities,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            'limit' => $limit,
            'total' => $total,
            'start' => $start,
            'end' => $end,
            'pagination_route' => 'tavro_admin_users_index'
        ]);

    }

    public function viewAction(Request $request, User $user)
    {
        return $this->render('TavroAdminBundle:Users:view.html.twig', [
            'user' => $user
        ]);

    }
}
