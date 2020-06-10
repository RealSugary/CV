package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.CardView;
import android.view.View;

public class LoginScreen extends AppCompatActivity {

    private TextInputLayout textInputUsername;
    private TextInputLayout textInputPassword;
    private CardView btn_login;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_loginscreen);

        textInputUsername = (TextInputLayout) findViewById(R.id.text_input_username);
        textInputPassword = (TextInputLayout) findViewById(R.id.text_input_password);
        btn_login = (CardView) findViewById(R.id.btn_login);

        btn_login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                validateFromDB();
            }
        });

    }

    private void validateFromDB() {

        String userInput = textInputUsername.getEditText().getText().toString().trim();
        String passwordInput = textInputPassword.getEditText().getText().toString().trim();

        if( userInput.isEmpty() ) {     // Ensure that Username can't be null
            textInputUsername.setError("Field cannot be empty");
        }else{
            DatabaseAccess databaseAccess = DatabaseAccess.getInstance(getApplicationContext()) ;
            databaseAccess.open();

            String db_Password = databaseAccess.findUser(userInput);

            databaseAccess.close();

            if(db_Password.equals(passwordInput) ) {
                Intent intent = new Intent(LoginScreen.this,MainMenu.class);
                startActivity(intent);
            }else{
                textInputPassword.setError("Wrong Username or Password");
            }
        }

    }

}
