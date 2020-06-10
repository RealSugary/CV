package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.view.View;
import android.widget.ImageView;

public class OfferRecord extends AppCompatActivity {

    private CardView btn_sap;
    private CardView btn_sup;
    private CardView btn_nue;
    private CardView btn_stat;
    private ImageView btn_back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offerrecord);

        btn_sap = (CardView) findViewById(R.id.btn_sap);
        btn_sup = (CardView) findViewById(R.id.btn_sup);
        btn_nue = (CardView) findViewById(R.id.btn_nue);
        btn_stat = (CardView) findViewById(R.id.btn_stat);
        btn_back = (ImageView) findViewById(R.id.btn_back);

        btn_sap.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferRecord.this,OfferSAP.class);
                startActivity(intent);
            }
        });

        btn_sup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferRecord.this,OfferSUP.class);
                startActivity(intent);
            }
        });

        btn_nue.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferRecord.this,OfferNUE.class);
                startActivity(intent);
            }
        });


        btn_stat.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferRecord.this,OfferSTAT.class);
                startActivity(intent);
            }
        });

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferRecord.this,MainMenu.class);
                startActivity(intent);
            }
        });

    }

}
