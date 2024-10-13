<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function index(Request $request){
        Stripe::setApiKey(env('STRIPE_KEY'));
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_ENDPOINT');

        $payload = $request->getContent();
        $sig_header = $request->headers->get('stripe-signature');
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch(UnexpectedValueException $e) {
            // Invalid payload
            return response()->json("Invalid payload", 400);
        } catch(SignatureVerificationException $e) {
            return response()->json("Signature error", 400);
        }


        switch ($event->type) {
            case 'account.updated':
                $account = $event->data->object;
                break;
            case 'account.external_account.updated':
            case 'account.external_account.deleted':
            case 'account.external_account.created':
                $externalAccount = $event->data->object;
                break;
            case 'customer.subscription.pending_update_applied':
            case 'customer.subscription.deleted':
            case 'customer.subscription.pending_update_expired':
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                break;
            case 'customer.created':
                $usercus=User::firstWhere('email',$event->data->object->email);
                if(!$usercus){
                    $usercus=User::create(["email"=>$event->data->object->email,"password"=>$this->generateRandomString(20),"name"=>$event->data->object->name?:"nom","firstname"=>""]);
                    $role = config('roles.models.role')::where('name', '=', 'Utilisateur')->first();  //choose the default role upon user creation.
                    $usercus->attachRole($role);
                    $usercus->save();
                    Password::sendResetLink(
                        ['email'=>$event->data->object->email]
                    );
                }
                $usercus->stripe_id=$event->data->object->id;
                $usercus->save();

                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }
        if(isset($subscription)){
            $user=User::firstWhere('stripe_id', $subscription->customer);

            if($user){
                $end_date=$subscription->ended_at?:$subscription->current_period_end;
                $user->sub_end_at=$end_date;
                $user->save();
            }

        }

        return response()->json("Ok", 200);
    }
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
