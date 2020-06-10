package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.view.View;
import android.widget.ImageView;

public class MainMenu extends AppCompatActivity {

    private CardView btn_offer_record;
    private CardView btn_gpa_calculator;
    private CardView btn_nonjupas_info;
    private ImageView btn_logout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mainmenu);

        btn_offer_record = (CardView) findViewById(R.id.btn_offer_record);
        btn_gpa_calculator = (CardView) findViewById(R.id.btn_gpa_calculator);
        btn_nonjupas_info = (CardView) findViewById(R.id.btn_nonjupas_info);
        btn_logout = (ImageView) findViewById(R.id.btn_logout);

        btn_offer_record.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainMenu.this,OfferRecord.class);
                startActivity(intent);
            }
        });

        btn_gpa_calculator.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainMenu.this,GPACalculator.class);
                startActivity(intent);
            }
        });

        btn_nonjupas_info.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainMenu.this,NonJupasInfo.class);
                startActivity(intent);
            }
        });

        btn_logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(MainMenu.this,LoginScreen.class);
                startActivity(intent);
            }
        });

    }

}
