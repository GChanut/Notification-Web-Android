package com.projet.notifcenter;



import oauth.signpost.OAuth;
import oauth.signpost.OAuthConsumer;
import oauth.signpost.commonshttp.CommonsHttpOAuthConsumer;
import oauth.signpost.commonshttp.CommonsHttpOAuthProvider;
import oauth.signpost.exception.OAuthCommunicationException;
import oauth.signpost.exception.OAuthExpectationFailedException;
import oauth.signpost.exception.OAuthMessageSignerException;
import oauth.signpost.exception.OAuthNotAuthorizedException;
import android.app.Activity;
import android.os.Bundle;
import junit.framework.Assert;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.util.Log;

public class MainActivity extends Activity {
	
	
    static String consumerKey = "icecondor-nest-"/*+ICECONDOR_VERSION*/;
    static String consumerSecret = "";
	private static final String TAG = "OAUTH";

	public static final String USER_TOKEN = "user_token";
	public static final String USER_SECRET = "user_secret";
	public static final String REQUEST_TOKEN = "request_token";
	public static final String REQUEST_SECRET = "request_secret";

	public static final String REQUEST_TOKEN_URL = "http://notifcenter.zapto.org/oauth2m/server/request_token.php";
	public static final String ACCESS_TOKEN_URL = "http://notifcenter.zapto.org/oauth2m/server/access_token.php";
	public static final String AUTHORIZE_URL = "http://notifcenter.zapto.org/oauth2m/server/authorize.php";

	private static final Uri CALLBACK_URI = Uri.parse("x-oauthflow://callback");

	public static final String PREFS = "MyPrefsFile";

	private OAuthConsumer mConsumer = null;
	private CommonsHttpOAuthProvider mProvider = null;
	
	SharedPreferences mSettings;
	
	
	

	
	
	public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
      

  
        mConsumer = new CommonsHttpOAuthConsumer("511084bd6aff5a79128b2ddeabc81c8f04ff8b228", "cfd941c23d21f01103451672c05dc374");
		
		mProvider = new CommonsHttpOAuthProvider (REQUEST_TOKEN_URL, ACCESS_TOKEN_URL, AUTHORIZE_URL);
		
		// It turns out this was the missing thing to making standard Activity launch mode work
		mProvider.setOAuth10a(true);
		
		mSettings = this.getSharedPreferences(PREFS, Context.MODE_PRIVATE);

		Intent i = this.getIntent();
		

		if (i.getData() == null) {
			try {
                                // This is really important. If you were able to register your real callback Uri with Twitter, and not some fake Uri
                                // like I registered when I wrote this example, you need to send null as the callback Uri in this function call. Then
                                // Twitter will correctly process your callback redirection

				
				String authUrl;
				
				authUrl = mProvider.retrieveRequestToken(mConsumer, CALLBACK_URI.toString());
				saveRequestInformation(mSettings, mConsumer.getToken(), mConsumer.getTokenSecret());

				
				Intent intent = new Intent(Intent.ACTION_VIEW);
				intent.setData(Uri.parse(authUrl));
				startActivity(intent);
				
	
			} catch (OAuthMessageSignerException e) {
				e.printStackTrace();
			} catch (OAuthNotAuthorizedException e) {
				e.printStackTrace();
			} catch (OAuthExpectationFailedException e) {
				e.printStackTrace();
			} catch (OAuthCommunicationException e) {
				e.printStackTrace();
			}
		}
	}

	@Override
	protected void onResume() {
		super.onResume();

		Uri uri = getIntent().getData();
		if (uri != null && CALLBACK_URI.getScheme().equals(uri.getScheme())) {
			String token = mSettings.getString(MainActivity.REQUEST_TOKEN, null);
			String secret = mSettings.getString(MainActivity.REQUEST_SECRET, null);
			Intent i = new Intent(this, Notif_CenterActivity.class); // Currently, how we get back to main activity.
			
			try {
				if(!(token == null || secret == null)) {
					mConsumer.setTokenWithSecret(token, secret);
				}
				String otoken = uri.getQueryParameter(OAuth.OAUTH_TOKEN);
				String verifier = uri.getQueryParameter(OAuth.OAUTH_VERIFIER);

				// We send out and save the request token, but the secret is not the same as the verifier
				// Apparently, the verifier is decoded to get the secret, which is then compared - crafty
				// This is a sanity check which should never fail - hence the assertion
				Assert.assertEquals(otoken, mConsumer.getToken());

				// This is the moment of truth - we could throw here
				mProvider.retrieveAccessToken(mConsumer, verifier);
				// Now we can retrieve the goodies
				token = mConsumer.getToken();
				secret = mConsumer.getTokenSecret();
				MainActivity.saveAuthInformation(mSettings, token, secret);
				// Clear the request stuff, now that we have the real thing
				MainActivity.saveRequestInformation(mSettings, null, null);
				i.putExtra(USER_TOKEN, token);
				i.putExtra(USER_SECRET, secret);
				
			} catch (OAuthMessageSignerException e) {
				e.printStackTrace();
			} catch (OAuthNotAuthorizedException e) {
				e.printStackTrace();
			} catch (OAuthExpectationFailedException e) {
				e.printStackTrace();
			} catch (OAuthCommunicationException e) {
				e.printStackTrace();
			} finally {
				startActivity(i); // we either authenticated and have the extras or not, but we're going back
				finish();
			}
		}
	}
	
	public static void saveRequestInformation(SharedPreferences settings, String token, String secret) {
		// null means to clear the old values
		SharedPreferences.Editor editor = settings.edit();
		if(token == null) {
			editor.remove(MainActivity.REQUEST_TOKEN);
			Log.d(TAG, "Clearing Request Token");
		}
		else {
			editor.putString(MainActivity.REQUEST_TOKEN, token);
			Log.d(TAG, "Saving Request Token: " + token);
		}
		if (secret == null) {
			editor.remove(MainActivity.REQUEST_SECRET);
			Log.d(TAG, "Clearing Request Secret");
		}
		else {
			editor.putString(MainActivity.REQUEST_SECRET, secret);
			Log.d(TAG, "Saving Request Secret: " + secret);
		}
		editor.commit();
		
	}
	
	public static void saveAuthInformation(SharedPreferences settings, String token, String secret) {
		// null means to clear the old values
		SharedPreferences.Editor editor = settings.edit();
		if(token == null) {
			editor.remove(MainActivity.USER_TOKEN);
			Log.d(TAG, "Clearing OAuth Token");
		}
		else {
			editor.putString(MainActivity.USER_TOKEN, token);
			Log.d(TAG, "Saving OAuth Token: " + token);
		}
		if (secret == null) {
			editor.remove(MainActivity.USER_SECRET);
			Log.d(TAG, "Clearing OAuth Secret");
		}
		else {
			editor.putString(MainActivity.USER_SECRET, secret);
			Log.d(TAG, "Saving OAuth Secret: " + secret);
		}
		editor.commit();
		
	}
	
}
