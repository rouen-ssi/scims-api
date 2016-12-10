<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Controllers\Admin;


use SciMS\Models\Account;
use SciMS\Models\AccountQuery;
use SciMS\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountController {

    /**
     * GET /admin/accounts
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response) {
        $accounts = AccountQuery::create()
          ->orderByUid('ASC')
          ->find();

        return $response->withJson([
            'results' => $accounts->getData(),
        ]);
    }

    /**
     * POST /admin/accounts
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response) {
        // Retrieves the parameters
        $email = $request->getParsedBodyParam('email', '');
        $firstName = $request->getParsedBodyParam('first_name', '');
        $lastName = $request->getParsedBodyParam('last_name', '');

        $account = new Account();
        $account->setUid(uniqid());
        $account->setEmail($email);
        $account->setFirstName($firstName);
        $account->setLastName($lastName);
        $account->setPassword('');

        // Returns a JSON containing errors if any.
        if (count($errors = Utils::validate($account)) > 0) {
            return $response->withJson([
                'errors' => $errors
            ], 400);
        }

        $account->save();

        return $response->withJson([
            'result' => $account,
        ]);
    }

    /**
     * PUT /admin/accounts/:uid
     * PATCH /admin/accounts/:uid
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function update(Request $request, Response $response, array $params) {
        if (!$accountUid = $params['uid']) {
            return $response->withStatus(404);
        }

        $account = AccountQuery::create()->findOneByUid($accountUid);
        $account->setEmail($request->getParsedBodyParam('email', ''));
        $account->setFirstName($request->getParsedBodyParam('first_name', ''));
        $account->setLastName($request->getParsedBodyParam('last_name', ''));
        $account->setRole($request->getParsedBodyParam('role', ''));
        $account->save();

        return $response->withJson([
            'result' => $account,
        ]);
    }

    /**
     * DELETE /admin/accounts/:uid
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function destroy(Request $request, Response $response, array $params) {
        if (!$accountUid = $params['uid']) {
            return $response->withStatus(400);
        }

        AccountQuery::create()->filterByUid($accountUid)->delete();

        return $response->withStatus(200);
    }
}
