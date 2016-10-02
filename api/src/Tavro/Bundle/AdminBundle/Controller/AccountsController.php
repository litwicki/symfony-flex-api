<?php

namespace Tavro\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\Account;

class AccountsController extends Controller
{
    public function indexAction(Request $request)
    {

        $thisPage = $request->get('page', 1);
        $limit = $request->get('limit', 2);

        $repo = $this->getDoctrine()->getManager()->getRepository('TavroCoreBundle:Account');
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

        return $this->render('TavroAdminBundle:Accounts:index.html.twig', [
            'accounts' => $entities,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            'limit' => $limit,
            'total' => $total,
            'start' => $start,
            'end' => $end,
            'pagination_route' => 'tavro_admin_accounts_index'
        ]);

    }

    public function viewAction(Request $request, Account $account)
    {
        return $this->render('TavroAdminBundle:Accounts:view.html.twig', [
            'account' => $account
        ]);

    }
}
