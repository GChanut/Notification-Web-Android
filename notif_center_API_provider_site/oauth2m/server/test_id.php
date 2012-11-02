<?php
// Dépendance
include_once "../library/OAuthStore.php";
include_once "../library/OAuthRequester.php";
require_once '../server/config.inc.php';
require_once '../library/OAuthRequestVerifier.php';

if (OAuthRequestVerifier::requestIsSigned())
{
        try
        {
                $req = new OAuthRequestVerifier();
                $user_id = $req->verify();

                // If we have an user_id, then login as that user (for this request)
                if ($user_id)
                {
                        echo $user_id;
                }
				else
				{
						echo "DEAD";
				}
        }
        catch (OAuthException $e)
        {
                // The request was signed, but failed verification
                header('HTTP/1.1 401 Unauthorized');
                header('WWW-Authenticate: OAuth realm=""');
                header('Content-Type: text/plain; charset=utf8');
                                        
                echo $e->getMessage();
                exit();
        }
}
?>