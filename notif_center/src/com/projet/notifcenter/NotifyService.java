package com.projet.notifcenter;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
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


import android.app.ActivityManager;
import android.app.ActivityManager.RunningServiceInfo;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.IBinder;
import android.os.Vibrator;
import android.util.Log;
import android.widget.Button;
import android.widget.Toast;

public class NotifyService extends Service {
	
	public static String data;
	
	public static int timeSync;
	
	

	private static final int NOTIFICATION_ID = 0;
	public static String responseText;
	public static int responseInt;
	public int intSaveRequest = 0;
	 private Button addNotificationBtn; 
	private Long counter = 0L; 
	private NotificationManager nm;
	private Timer timer = new Timer();
	private final Calendar time = Calendar.getInstance();
	@Override
	public IBinder onBind(Intent intent) {
		return null;
	}
	
	
	@Override
	public void onCreate() {
		super.onCreate();
		
		nm = (NotificationManager)getSystemService(NOTIFICATION_SERVICE);
		Toast.makeText(this,"Service créé", Toast.LENGTH_LONG).show();
			
		incrementCounter();
		
		
	}
	
	public void onStart(Intent intent, int startId) {
		 super.onStart(intent, startId);
		  
		 Bundle bundle = intent.getExtras();
		 data = bundle.getString("extraData"); // récuperation user
		 Log.e("name", data);
		 String data2 = bundle.getString("extraData2"); // récupération le temps de synchro
		 timeSync = Integer.parseInt(data2);
		 System.out.println(timeSync);
		 
		//Appel de la loop de temps pour envoyé les requete au serveur de webCV
		  ClassExecutingTask executingTask = new ClassExecutingTask();
		  executingTask.start(timeSync);

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
	

	
	
	private final void showNotification(int req){ 
        //Récupération du notification Manager 
        final NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE); 
  
        //Création de la notification avec spécification de l'icône de la notification et le texte qui apparait à la création de la notification 
        final Notification notification = new Notification(R.drawable.cvimg, "WebCV", System.currentTimeMillis()); 
  
        //Définition de la redirection au moment du clic sur la notification. Dans notre cas la notification redirige vers notre application 
        final PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, new Intent(this, QuickPrefsActivity.class), 0); 
        notification.flags = Notification.DEFAULT_LIGHTS | Notification.FLAG_AUTO_CANCEL;
        
        
        //Récupération du titre et description de la notification 
        final String notificationTitle = "WebCV visites"; 
        final String notificationDesc = "Votre CV à été visité "+req+" fois";        
  
        //Notification & Vibration 
        notification.setLatestEventInfo(this, notificationTitle, notificationDesc, pendingIntent); 
        notification.vibrate = new long[] {0,200,100,200,100,200}; 
        
        //faire un test si les vibrations sont activés ou pas dans la liste preference

  
        notificationManager.notify(NOTIFICATION_ID, notification); 
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
		HttpPost httppost = new HttpPost("http://notifcenter.zapto.org/application/"+usr+"CV.php");
		
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


public class ClassExecutingTask extends NotifyService{
	 long delay; 
    LoopTask task = new LoopTask();
    Timer timers = new Timer("TaskName");
    

    
    public void start(int val) {	
    	
	    timers.cancel();
	    timers = new Timer("TaskName");
	    delay = val*1000; // delay in ms : 10 * 1000 ms = 10 sec.
	    Date executionDate = new Date(); // no params = now
	    timers.scheduleAtFixedRate(task, executionDate, delay);
    	
    }
    

   
    
    private class LoopTask extends TimerTask {
		    public void run() {
		    	if(isMyServiceRunning() == true){
		    		Log.d("if","il passe");
			    	showNotification(getRequestCV(data)); 
			    	
		    	
		    	}else{stop();}
		    	
		    }
		    
		    public void stop() {
		    	Log.d("stop","stop?");
		    	timers.cancel();
		    }
		    

    }
  }
}


