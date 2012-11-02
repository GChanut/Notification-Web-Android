package com.projet.notifcenter;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import java.io.UnsupportedEncodingException;

import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;

import java.util.ArrayList;


import java.util.List;
import java.util.Timer;
import java.util.TimerTask;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import junit.framework.Assert;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpParams;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


import android.app.ActivityManager;
import android.app.ActivityManager.RunningServiceInfo;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.os.IBinder;
import android.os.PowerManager;
import android.os.PowerManager.WakeLock;
import android.util.Log;

import android.widget.Toast;



public class NotifyService extends Service {
	
	public static String data, notif, vibPref, ringPref;
	private static JSONArray appli = null;
	public static int timeSync;
	public static String nameAppli, multiChoix;
	public static String resultNotif[];
	public static  String str[];
	static int aze;
	public static String responseText;
	public static int responseInt;
	public int intSaveRequest = 0;

	private Long counter = 0L; 
	private NotificationManager nm;
	private Timer timer = new Timer();

	@Override
	public IBinder onBind(Intent intent) {
		return null;
	}
	
	
	@Override
	public void onCreate() {
		super.onCreate();

		nm = (NotificationManager)getSystemService(NOTIFICATION_SERVICE);
		Toast.makeText(this,"Service créé", Toast.LENGTH_LONG).show();


	}
	
	public void onStart(Intent intent, int startId) {
		 super.onStart(intent, startId);
			PowerManager mgr = (PowerManager)getSystemService(Context.POWER_SERVICE);
			WakeLock wakeLock = mgr.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK, "MyWakeLock");
			wakeLock.acquire();
		 

		 
         Bundle bundle = intent.getExtras();
		
		 data = bundle.getString("extraData"); // récuperation user
		 Log.e("name", data);
		 String data2 = bundle.getString("extraData2"); // récupération le temps de synchro
		 timeSync = Integer.parseInt(data2);
		 Log.e("temps de synchro", data2);
		 multiChoix = bundle.getString("multichoice"); // récupération des objets checked dans le multiple choix
		 vibPref= bundle.getString("vib"); //recuperation etat vibration
		 ringPref= bundle.getString("ringtone"); //recuperation etat sonnerie
		 


		 //Appel de la loop de temps 
		 new Thread(new Runnable(){
			    public void run() {
			    	try {
			    		// TODO Auto-generated method stub
			    		while(isMyServiceRunning() == true){
			    			
				    		str= multiChoix.split("OV=I=XseparatorX=I=VO");
				    		getNotifByMultiChoice(str);
				    		Thread.sleep(timeSync*1000);
			    		} 
			    		
			    	
			    	}catch (InterruptedException e) {
					// TODO Auto-generated catch block
			    		e.printStackTrace();
			    	}
			       
			    
			    	
			    	stopSelf();
			    }
			}).start();
		 

		 
		}//end onStart
		

		public void getNotifByMultiChoice(String choice[]){
			try {	
				String var[] = new String[QuickPrefsActivity.getNumberApplication()];
				aze = 1000;
				for(int i = 0; i<choice.length; i++){ //On regarde combien d'application on été coché
										
					JSONParser jParser = new JSONParser();		
					JSONObject json = jParser.getJSONFromLink("http://notifcenter.zapto.org/notifcenter/oauth2m/api/json/"+data+".json");				
					appli = json.getJSONArray("application");
			
					for(int j = 0; j < appli.length(); j++){ //On récupère toute nos applications de notre json
						JSONObject c = appli.getJSONObject(j);		
						nameAppli = c.getString("application_name");	
						 var[j] = nameAppli;                  //On les range dans une array
					}
					
					for(int k=0; k<var.length; k++)	{
						//Si le nom d'un item coché est égal à une application dans le json
						if(choice[i].equals(var[k])){
							//récupération du champs notificaiton dans le json
							JSONObject c = appli.getJSONObject(k);		
							notif = c.getString("notification"); //la var notif étant les notifications du json d'une des applications cochées encoder en String html
							
							//On upload le json
							DefaultHttpClient client = new DefaultHttpClient();
							HttpGet request = new HttpGet();								
							request.setURI(new URI("http://notifcenter.zapto.org/notifcenter/oauth2m/api/updateNotif.php?user="+data+"&app="+var[k]));
							client.execute(request);
							
							if(notif.equals("null") || notif.equals("")){
								
							}else{

								
								//Recuperation intérieur des balises href
								Pattern p = Pattern.compile("<a href=\"(.*?)\" ");
								
								Matcher url = p.matcher(notif);
								Pattern p2 = Pattern.compile("<h4>(.*?)</h4>");
								Matcher content = p2.matcher(notif);
								
								while(url.find()) {	
									   content.find();
									   System.out.println(url.group(1));
									   System.out.println(content.group(1));
									   showNotification(var[k], content.group(1), aze, url.group(1));
									   aze++; //Compteur pour avertir plusieurs notif d'une même appli

									
								}
								
								


									
								Log.e("html parse", notif);
								
							}
						}
					}
				}
				
			}catch (JSONException e) {
				e.printStackTrace();
			} catch (URISyntaxException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
			
			
		}
	
	@Override
	public void onDestroy() {
		super.onDestroy();
        // Cancel the persistent notification.
		shutdownCounter();
        nm.cancel(R.string.service_started);
		Toast.makeText(this, "Service detruit", Toast.LENGTH_LONG).show();
		counter=null;

	}
	
	private void showNotification(String nameAppli, String description, int count, String url){ 
        //Récupération du notification Manager 
         NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE); 

        //Création de la notification avec spécification de l'icône de la notification et le texte qui apparait à la création de la notification 
         Notification notification = new Notification(R.drawable.cvimg, nameAppli, System.currentTimeMillis()); 
        Intent intentNotification = new Intent(Intent.ACTION_VIEW,Uri.parse(url));
        //Définition de la redirection au moment du clic sur la notification. Dans notre cas la notification redirige vers notre application 
         PendingIntent pendingIntent = PendingIntent.getActivity(this, 0,intentNotification, 0); 
        notification.flags =  Notification.FLAG_AUTO_CANCEL;
        if(ringPref.equals("true")){
        	 notification.defaults |= Notification.DEFAULT_SOUND;
        }
       
        
        if(vibPref.equals("true")){
        	notification.defaults |= Notification.DEFAULT_VIBRATE;
        }
        //Récupération du titre et description de la notification 
        String notificationTitle = nameAppli; 
        String notificationDesc = description;        
  
        //Notification & Vibration 
        notification.setLatestEventInfo(this, notificationTitle, notificationDesc, pendingIntent); 
        
        
        //faire un test si les vibrations sont activés ou pas dans la liste preference

  
        notificationManager.notify(count, notification); 
    }
	
	 
    
    private void incrementCounter() {
    	timer.scheduleAtFixedRate(new TimerTask(){ public void run() {counter++;}}, 0, 1000L);
    }
    
    private void shutdownCounter() {
    	if (timer != null) {
    		timer.cancel();
    	}
    }
    
    
   

    private boolean isMyServiceRunning() {
        ActivityManager manager = (ActivityManager) getSystemService(Context.ACTIVITY_SERVICE);
        for (RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {

            if ("com.projet.notifcenter.NotifyService".equals(service.service.getClassName())) {
                return true;
            }
        }
        return false;
    }


private int getRequestCV(String usr) {
		
		try {
		HttpParams params = new BasicHttpParams();

		SchemeRegistry registry = new SchemeRegistry();
        registry.register(new Scheme("x-oauthflow",PlainSocketFactory.getSocketFactory(), 80));
        registry.register(new Scheme("http",PlainSocketFactory.getSocketFactory(), 80));
        ThreadSafeClientConnManager manager = new ThreadSafeClientConnManager(params, registry);
        HttpClient client = new DefaultHttpClient(manager, params);
		HttpPost httppost = new HttpPost("http://notifcenter.zapto.org/webCV/cvs/"+usr+"CV.php");
		
	    List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(); 
	    nameValuePairs.add(new BasicNameValuePair("access", ""));
	    
		httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

	    HttpResponse response = client.execute(httppost);
	    HttpEntity entity = response.getEntity();

	    
	    responseText = EntityUtils.toString(entity);   
	    responseInt = Integer.parseInt(responseText);
	    System.out.println(responseInt);

		
		} catch (UnsupportedEncodingException e) {		
			e.printStackTrace();
		} catch (ClientProtocolException e) {		
			e.printStackTrace();
		} catch (IOException e) {			
			e.printStackTrace();
		}
		return responseInt;
	}





}


