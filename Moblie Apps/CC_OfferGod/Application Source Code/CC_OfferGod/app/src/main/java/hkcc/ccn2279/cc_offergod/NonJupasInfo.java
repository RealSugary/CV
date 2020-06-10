package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.view.View;
import android.widget.ImageView;

public class NonJupasInfo extends AppCompatActivity {

    private CardView btn_cityu;
    private CardView btn_cuhk;
    private CardView btn_hkbu;
    private CardView btn_hku;
    private CardView btn_hkust;
    private CardView btn_polyu;
    private ImageView btn_back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_nonjupasinfo);

        btn_cityu = (CardView) findViewById(R.id.btn_cityu);
        btn_cuhk = (CardView) findViewById(R.id.btn_cuhk);
        btn_hkbu = (CardView) findViewById(R.id.btn_hkbu);
        btn_hku = (CardView) findViewById(R.id.btn_hku);
        btn_hkust = (CardView) findViewById(R.id.btn_hkust);
        btn_polyu = (CardView) findViewById(R.id.btn_polyu);
        btn_back = (ImageView) findViewById(R.id.btn_back);


        btn_cityu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://banweb.cityu.edu.hk/pls/PROD/hwskalog_cityu.P_DispLoginNon");
                startActivity(intent);
            }
        });

        btn_cuhk.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://nweb.adm.cuhk.edu.hk/adm_online/public/account/SAC00001.aspx");
                startActivity(intent);
            }
        });

        btn_hkbu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://iss.hkbu.edu.hk/amsappl_nj/signin.jsf");
                startActivity(intent);
            }
        });

        btn_hku.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://ug.hku.hk/hku-applicant/hku/index/login.xhtml");
                startActivity(intent);
            }
        });

        btn_hkust.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://w5.ab.ust.hk/util/cgi-bin/login.cgi?redirect=1&back=https%3A%2F%2Fw5.ab.ust.hk%2Fcgi-bin%2F9u_cgi.sh%2FWService%3Dbroker_9u_p%2Fprg%2Fug_func_2f.r%3Ff_func_hdr%3Dug_ap_func_hdr%26f_func_page%3Dug_ap_appl_summ%26f_func_cde%3DAA01%26f_dummy%3D12542&MYRealm=APU9&");
                startActivity(intent);
            }
        });

        btn_polyu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,Webrowser.class);
                intent.putExtra("url","https://www38.polyu.edu.hk/eAdmission/index.jsf");
                startActivity(intent);
            }
        });

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NonJupasInfo.this,MainMenu.class);
                startActivity(intent);
            }
        });

    }

}