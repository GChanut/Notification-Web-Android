package com.projet.notifcenter;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.projet.notifcenter.NotifyService.ClassExecutingTask;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;

import android.preference.CheckBoxPreference;
import android.preference.ListPreference;
import android.preference.Preference;
import android.preference.Preference.OnPreferenceClickListener;
import android.preference.PreferenceActivity;
import android.util.Log;




public class QuickPrefsActivity extends PreferenceActivity implements SharedPreferences.OnSharedPreferenceChangeListener { 
   
	public static String currText;
	public static String resultActivity = "noname";
	public static final String UPDATE_PREF = "updates_interval";
	private static final String TAG_CONTACTS = "contacts"; //Nom de la table json
	private static final String TAG_APPLICATION = "application"; //Nom de la table json
	private static final String TAG_NAME = "name"; //Nom d'une colonne json
	private static final String TAG_APPLI_NAME = "application_name";  //Nom d'une colonne json
	private static JSONArray contacts = null;
	private static JSONArray appli = null;
	
	private static String name;
	private static String app;

	
    private ArrayList<ListPreference> mListPreferences;  
    private String[] mListPreferencesKeys = new String[] {  
     "updates_interval" // The 'android:key' value of the ListPreference  
    };  

    
    @Override
    public void onCreate(Bundle savedInstanceState) {    	
        super.onCreate(savedInstanceState); 
        
        //Récupération des données de l'autre Activity
        Bundle extra = getIntent().getExtras();   
        if (extra != null) {
            resultActivity = extra.getString("transfer");          
        }   
       
        addPreferencesFromResource(R.xml.preferences);  
              
        Intent myIntent = new Intent(QuickPrefsActivity.this, NotifyService.class);
        
        ListPreference listPreference = (ListPreference) findPreference("updates_interval");
        currText = listPreference.getValue();
        final Bundle bundle = new Bundle();
        bundle.putCharSequence("extraData", resultActivity);
        bundle.putCharSequence("extraData2", currText);
        myIntent.putExtras(bundle);
            
      // PendingIntent pendingIntent = PendingIntent.getService(QuickPrefsActivity.this, 0, myIntent, 0);
             
       
              
              mListPreferences = new ArrayList<ListPreference>();  
              SharedPreferences sharedPrefs = getPreferenceManager().getSharedPreferences();  
              sharedPrefs.registerOnSharedPreferenceChangeListener(this);  
              
              for (String prefKey : mListPreferencesKeys) {	  
	               ListPreference pref = (ListPreference)getPreferenceManager().findPreference(prefKey);  
	               mListPreferences.add(pref);  
	               onSharedPreferenceChanged(sharedPrefs, prefKey);  
              }  
              
              
              
              
              
              
             
  
              
/////////////////////////////////////////////////////////////////////////////////
              
              //Si la synchronisation de données est Checked
              
              CheckBoxPreference syncData = (CheckBoxPreference)findPreference("update_synchro");
              
              if(syncData.isChecked()){  //Demarrage du service s'il est déja checked       	 
            	 // startService(new Intent(QuickPrefsActivity.this, NotifyService.class));         	  
            	  Intent serviceIntent = new Intent(QuickPrefsActivity.this, NotifyService.class);
                  serviceIntent.putExtras(bundle);
                  this.startService(serviceIntent);       	    
            }
             
              //Si on change de valeurs
              syncData.setOnPreferenceChangeListener(new Preference.OnPreferenceChangeListener() {            
                  public boolean onPreferenceChange(Preference preference, Object newValue) {
                	  if(newValue.toString().equals("true")){
                		  Intent serviceIntent = new Intent(QuickPrefsActivity.this, NotifyService.class);
                          serviceIntent.putExtras(bundle);
                		 startService(serviceIntent);
                	  }
                	  if(newValue.toString().equals("false")){
                		  stopService(new Intent(QuickPrefsActivity.this, NotifyService.class));
                	  }
                           
                      return true;
                  }
              }); 
                  
 ////////////////////////////////////////////////////////////////////////////             

    
              //Lorsqu'on clique sur le listview de la deconnexion
              Preference customPref = (Preference) findPreference("disconnect");
              customPref.setOnPreferenceClickListener(new OnPreferenceClickListener() {

                        public boolean onPreferenceClick(Preference preference) {
                          	//stop le service et redirection vers le login				        								
                             stopService(new Intent(QuickPrefsActivity.this,NotifyService.class));    
                             stopService(new Intent(QuickPrefsActivity.this, ClassExecutingTask.class));
                             Intent i = new Intent(QuickPrefsActivity.this, Main.class);
                             startActivity(i);
                             finish();
                             return true;   
                          }
                       });
              
              
              //Obtention de l'user qui se connecte
              String user = getUser();
              Preference etp = (Preference) findPreference("customPref");
              etp.setTitle(user);
    }  
    //Fin du OnCreate
        
       
       public void onSharedPreferenceChanged(SharedPreferences pref, String prefKey) {  
           for (ListPreference listPref : mListPreferences) {  
            if (listPref.getKey().equals(prefKey))  
             listPref.setSummary(listPref.getEntry());  
           }  
       }  
      

   
	
	public String getUser(){
    	 
    	JSONParser jParser = new JSONParser();
    	
		// getting JSON string from URL
		JSONObject json = jParser.getJSONFromLink("http://notifcenter.zapto.org/api/json/"+resultActivity+".json");
		
		//JSONObject json = jParser.getJSONFromUrl("http://andro.franceserv.com/sysadmin.json");
		try {
			// Getting Array of Contacts
			contacts = json.getJSONArray(TAG_CONTACTS);
			Log.d("getuser",json.toString());
			
			// looping through All Contacts
			for(int i = 0; i < contacts.length(); i++){
				JSONObject c = contacts.getJSONObject(i);		
				// Storing each json item in variable
				 name = c.getString(TAG_NAME);			 
			}			
			
		} catch (JSONException e) {
			e.printStackTrace();
		}
		
    	return name;
    }
    
    
  
	public static CharSequence[] getApplications(){
    	JSONParser jParser = new JSONParser();
    	CharSequence var[] = new String[4]; //peut causé des problème
		// getting JSON string from URL
    	
		JSONObject json = jParser.getJSONFromLink("http://notifcenter.zapto.org/api/json/"+resultActivity+".json");
		
    	try {
			// Getting Array of Contacts
			appli = json.getJSONArray(TAG_APPLICATION);
			Log.d("errorpref",appli.toString());
			//System.out.println(json.getJSONArray(TAG_APPLICATION));
			
			// looping through All Contacts
			for(int i = 0; i < appli.length(); i++){
				JSONObject c = appli.getJSONObject(i);
				
				//if(c.getString(TAG_APPLI_STATE).equals("true")){
					app = c.getString(TAG_APPLI_NAME);
					var[i]= app;
				//}
			}
					
		} catch (JSONException e) {
			e.printStackTrace();
		}  
    return var;
    }
    
    
    
}