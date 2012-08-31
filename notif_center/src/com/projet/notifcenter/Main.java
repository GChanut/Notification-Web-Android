package com.projet.notifcenter;



import android.app.Activity;
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
}
