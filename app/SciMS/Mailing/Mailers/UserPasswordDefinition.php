<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Mailing\Mailers;


use SciMS\Mailers\Mailer;
use SciMS\Models\Account;

/**
 * This gets sent to a user so that he can finish its registration.
 *
 * Class UserPasswordDefinition
 * @package SciMS\Mailing\Mailers
 */
class UserPasswordDefinition extends Mailer
{
    /**
     * @var Account
     */
    private $account;

    /**
     * UserPasswordDefinition constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->account->getEmail();
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return "You have been invited to SciMS";
    }

    /**
     * @return void
     */
    protected function renderAsHtml()
    {
    ?>
        <!DOCUMENT html>
        <html>
        <head>
            <title><?php echo $this->getSubject() ?></title>
        </head>

        <body>
            <h1><?php echo $this->getSubject() ?></h1>

            <p>
                Hello, <?php echo $this->account->getFirstName() ?> !
            </p>

            <p>
                You have been personnally invited to join our new SciMS community as an article editor.
                We need you to <a href="<?php echo $this->getRegistrationUrl() ?>">end your account registration</a>
                in order to start posting awesome articles!
            </p>

            <p>
                Best Regards,
            </p>
        </body>
        </html>
    <?php
    }

    private function getRegistrationUrl()
    {
        return implode("/", [FRONTEND_URL, "register", $this->generateRegistrationToken()]);
    }

    private function generateRegistrationToken()
    {
        $token = base64_encode(openssl_random_pseudo_bytes(64));
        $this->account->setToken($token);
        $this->account->setTokenExpiration(time());
        $this->account->save();
        return $token;
    }
}