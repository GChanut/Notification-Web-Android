package com.projet.notifcenter;



import android.app.Activity;
import android.app.ActivityManager;
import android.app.ActivityManager.RunningServiceInfo;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;

public class Main extends Activity{
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.login);
		
		 final EditText login = (EditText) findViewById(R.id.login);
		 final EditText pass = (EditText) findViewById(R.id.password);
		final Button startButton = ((Button) findViewById(R.id.start_button));	
		
		if(isMyServiceRunning() == true){
			Intent intent = new Intent(Main.this, QuickPrefsActivity.class);
			startActivity(intent);
			finish();
		}
		
		
		CheckBox rCB = (CheckBox)findViewById(R.id.remember);
		if(rCB.isChecked()){
				
		}
		
		
  	  	startButton.setOnClickListener(new OnClickListener() {	
			public void onClick(View v) {
				Intent intent = new Intent(Main.this, OauthConnect.class);
				intent.putExtra("usrnm", login.getText().toString());
				intent.putExtra("pswrd", pass.getText().toString());
				startActivity(intent);
				finish();
			}
		});
  	  	
  	  	
  	  	
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
}
