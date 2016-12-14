<?php

namespace App\Http\Controllers;


use App\Models\OAuth\AccessToken;
use Illuminate\Http\Request;
use Authorizer;

/**
 * Class OAuthController
 * @package App\Http\Controllers
 */
class OAuthController
{

    /**
     * @SWG\Post(
     *      path="/oauth/access_token",
     *      summary="Generate an API token with your username and password",
     *      description="Generate an API token with your username and password",
     *      tags={"oauth"},
     *      operationId="CreateAccessToken",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      parameters={},
     *      @SWG\Parameter(
     *          name="client_id",
     *          in="formData",
     *          description="The generated client Id for your apiKey",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="client_secret",
     *          in="formData",
     *          description="The generated client secret for your apiKey",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="grant_type",
     *          in="formData",
     *          description="The type of authentication you want to attempt",
     *          required=true,
     *          type="string",
     *          default="password",
     *          enum={"password"}
     *      ),
     *      @SWG\Parameter(
     *          name="username",
     *          in="formData",
     *          description="The username you are attempting to authenticate with",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          description="The password you are attempting to authenticate with",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/AccessToken")
     *     ),
     *      @SWG\Response(
     *          response=400,
     *          description="Invalid credentials"
     *      ),
     * )
     *
     *
     * @param   Request $request
     * @return  AccessToken
     */
    public function issueAccessToken (Request $request)
    {
        $access_token                   = Authorizer::issueAccessToken();
        return response($access_token);
    }

}