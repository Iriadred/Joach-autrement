<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\tokenInscription;
use App\Mail\tokenResetPassword;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    

    /**
     * It's generating a random token. If exist, he regenerates. It's creating a new token and saving
     * it in the database
     * 
     * @param int idUsers The user's id
     * @param string reason It's the reason why the token is generated.
     * 
     * @return The token is being returned.
     */
    private function generationToken(int $idUsers, string $reason)
    {
        /* It's generating a random token. If exist, he regenerates*/
        do {
            $randomToken = Str::random(64);
        } while (Token::where('token', $randomToken)->exists());

        /* It's creating a new token and saving it in the database. */
        $nb= Token::count('idToken');
        $token = new Token;
        $idToken =  $nb + 1;
        $token->idToken = $idToken;
        $token->idUsers = $idUsers;
        $token->token = $randomToken;
        $token->reasonToken = $reason;
        $token->save();

        return $randomToken;
    }

    

    

    public function TokenRegisterEleve(Request $request)
    {
        /* It's getting the id of the user with the token. */
        $idUsers = Token::select('idUsers')->where('token', $request->token)->take(1)->get();
        foreach ($idUsers as $ids) {
            $id = $ids["idUsers"];
        }

        /* It's checking if the token exists and if it's not used. If it's true, it's updating the
        token and the user. If it's false, it's redirecting the user to the home page. */
        if (Token::where('token', $request->token)->where('dateUtilisation', null)->where('reasonToken', "confirmation-eleve")->exists()) {
            Token::where('token', $request->token)->update(['dateUtilisation' => date('Y-m-d H:i:s', time())]);
            User::where('premiereConnexion', false)->where('idUsers', $id)->update(['validationMail' => 1]);
            session()->flash('success', 'Félicitations, votre adresse mail est confirmée');
            return redirect('/login');
        } else {
            if (Token::where('token', $request->token)->where('reasonToken', "confirmation-eleve")->exists()) {
                session()->flash('success', 'Email déjà confirmé');
            } else {
                session()->flash('alert', 'Token innexistant');
            }

            return redirect('/login');
        }
    }



    public function VerfiRegister(Request $request)
    {

        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'class' => ['required', 'integer'],
            'name' => ['required', 'integer'],
            /* A validation rule for password. */
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            /* Verification norme Mail */
            'email' => ['email:rfc,dns', 'unique:App\Models\User,emailUsers'],
        ]);

        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator->errors())
                ->withInput();
        }

        /* It's updating the user with the id `->name` and the class `->class` with the email `->email` and the password `->password`. */
        User::where('idUsers', $request->name)->where('idClasse', $request->class)->update(['premiereConnexion' => 0, 'emailUsers' => $request->email, 'mdpUsers' => Hash::make($request->password)]);

        // Création d'un token avec la fonction privé generationToken
        $randomToken = $this->generationToken($request->name, 'confirmation-eleve');

        /* It's sending an email to the user with a confirmation link. */
        Mail::to($request->email)->send(new tokenInscription($randomToken));

        /* It's sending a message to the user and redirecting him to the login page. */
        session()->flash('success', 'Un mail de confirmation a été envoyé à l\'adresse ' . $request->email . "\n(Si vous ne recevez pas le mail, vérifiez vos spams)");
        return redirect('/login');
    }

    

    

    public function resetPassView()
    {

        return view('auth.reset-pw');
    }

    public function resetPasswordSendMail(Request $request)
    {
        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            /* Verification norme Mail */
            'email' => ['email:rfc'],
        ]);

        /* It's checking if the input is valid. If it's false, it's redirecting the user to the page
        `/forget-password` with the errors and the input. */
        if ($validator->fails()) {
            return redirect('/forget-password')
                ->withErrors($validator->errors())
                ->withInput();
        }

        /* It's checking if the email exists in the database. If it's false, it's sending a message to
        the user and redirecting him to the login page. */
        if (!User::where('emailUsers', $request->email)->exists()) {
            session()->flash('alert', 'Adresse mail introuvable');
            return redirect('/forget-password');
        }

        /* It's getting the id of the user with the email. */
        $idUsers = User::select('idUsers')->where('emailUsers', $request->email)->take(1)->get();
        foreach ($idUsers as $ids) {
            $id = $ids["idUsers"];
        }

        // Création d'un token avec la fonction privé generationToken
        $randomToken = $this->generationToken($id, 'reset-password');

        /* It's sending an email to the user with a confirmation link. */
        Mail::to($request->email)->send(new tokenResetPassword($randomToken));

        /* It's sending a message to the user and redirecting him to the login page. */
        session()->flash('success', 'Un lien a été envoyé à l\'adresse suivante : ' . $request->email. "\n(Si vous ne recevez pas le mail, vérifiez vos spams)");
        return redirect('/login');
    }

    public function newPassword($token)
    {
        /* It's checking if the token exists and if it's not used. If it's true, it's updating the
        token and the user. If it's false, it's redirecting the user to the home page. */
        if (Token::where('token', $token)->where('dateUtilisation', null)->where('reasonToken', "reset-password")->exists()) {
            /* It's getting the id of the user with the token. */
            $idUsers = Token::select('idUsers')->where('token', $token)->take(1)->get();
            foreach ($idUsers as $ids) {
                $id = $ids["idUsers"];
            }
            return view('auth.new-pw', ['idUser' => $id, 'token' => $token]);
        } else {
            /* It's checking if the token exists and if it's not used. If it's true, it's updating the
                    token and the user. If it's false, it's redirecting the user to the home page. */
            if (Token::where('token', $token)->where('reasonToken', "reset-password")->exists()) {
                session()->flash('success', 'Mot de passe déjà modifié');
            } else {
                session()->flash('alert', 'Token innexistant');
            }

            return redirect('/login');
        }
    }

    public function setNewPassword(Request $request)
    {

        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails() || !Token::where('token', $request->token)->where('reasonToken', "reset-password")->where('idUsers', $request->idUser)->exists()) {
            session()->flash('alert', 'Une erreur est survenue');
            return redirect('/login');
        }

        /* It's updating the user with the id `->name` and the class `->class` with the email `->email` and the password `->password`. */
        User::where('idUsers', $request->idUser)->update(['mdpUsers' => Hash::make($request->password)]);
        session()->flash('success', 'Votre mot de passe à bien été modifié');
        Token::where('token', $request->token)->update(['dateUtilisation' => date('Y-m-d H:i:s', time())]);
        return redirect('/login');
    }
}
