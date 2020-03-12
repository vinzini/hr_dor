<?php
namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;
use Zend\Diactoros\Response as Psr7Response;
use Auth;

class AccessTokenController extends ATC
{
    public function issueToken(ServerRequestInterface $request)
    {
        $grant_type = $request->getParsedBody()[config('general.grant_type')];
        if($grant_type == config('general.grant_type_refresh')){
            $res = $this->withErrorHandling(function () use ($request) {
                return  $this->convertResponse(
                    $this->server->respondToAccessTokenRequest($request, new Psr7Response)
                );
            });
            $data = json_decode($res->getContent(), true);
            $auth = Auth::check() ? true : false;

            if(isset($data["error"]))
                return response()->fail($auth,$data['message']);

            if($auth){
                $user = collect(Auth::user())->only('name','email','role_id','country_code');
                $user->put('access_token', $data['access_token']);
                $user->put('refresh_token', $data['refresh_token']);
                return response()->success(true,'Successfully Created',$user);
            }else{
                return response()->fail($auth,'Something Went Wrong2');
            }
        }else{
            try {
                //get username (default is :email)
                $username = $request->getParsedBody()['username'];

                //get user
                //change to 'email' if you want
                $user = User::select('name','email','role_id','country_code')->where('email', '=', $username)->first();
                //$user = User::where('email', '=', $username)->first();

                //generate token
                $tokenResponse = parent::issueToken($request);

                //convert response to json string
                $content = $tokenResponse->getContent();

                //convert json to array
                $data = json_decode($content, true);


                if(isset($data["error"]))
                    return response()->fail(false,'The user credentials were incorrect.');
                    //throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);



                //add access token to user
                $user = collect($user);
                $user->put('access_token', $data['access_token']);
                //if you need to send out token_type, expires_in and refresh_token in the response body uncomment following lines
                //$user->put('token_type', $data['token_type']);
                //$user->put('expires_in', $data['expires_in']);
                $user->put('refresh_token', $data['refresh_token']);

                return response()->success(true,'Successfully Logged In',$user); 
                //return Response::json(array($user));
            }
            catch (ModelNotFoundException $e) { // email not found
                //return error message
                //return response(["message" => "User not found"], 500);
                return response()->fail(false,'Something Went Wrong');
            }
            catch (OAuthServerException $e) { //password not correct..token not granted
                //return error message
                //return response(["message" => "The user credentials were incorrect.', 6, 'invalid_credentials"], 500);
                return response()->fail(false,'The user credentials were incorrect');

            }
            catch (Exception $e) {
                ////return error message
                //return response(["message" => "Internal server error",'msg'=>$e->getMessage()], 500);
                return response()->fail(false,'Something Went Wrong');

            }
        }
    }
}