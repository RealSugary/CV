package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import java.util.ArrayList;

public class OfferSTAT extends AppCompatActivity {

    private ImageView btn_back;

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_year = new ArrayList<>();
    private ArrayList<String> str_avg = new ArrayList<>();
    private ArrayList<String> str_mini = new ArrayList<>();
    private ArrayList<String> str_max = new ArrayList<>();


    private RecyclerView recyclerView_stat;
    private OfferSTATAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offerstat);

        btn_back = (ImageView) findViewById(R.id.btn_back);

        displayData();

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferSTAT.this,OfferRecord.class);
                startActivity(intent);
            }
        });

    }

    private void displayData() {

        recyclerView_stat = (RecyclerView) findViewById(R.id.recyclerView_stat);
        adapter = new OfferSTATAdapter(str_uname, str_year, str_avg, str_mini, str_max,this);
        recyclerView_stat.setAdapter(adapter);
        recyclerView_stat.setLayoutManager( new LinearLayoutManager(this) );

        DatabaseAccess databaseAccess = DatabaseAccess.getInstance(getApplicationContext()) ;
        databaseAccess.open();

        Cursor data = databaseAccess.getstat();

        if ( data != null ) {
            if ( data.moveToFirst() ) {
                do{
                    str_uname.add( data.getString(0) );
                    str_year.add( data.getString(1) );
                    str_avg.add( data.getString(2) );
                    str_mini.add( data.getString(3) );
                    str_max.add( data.getString(4) );
                }while ( data.moveToNext() );
            }
        }else {
            databaseAccess.close();
        }

        adapter.notifyDataSetChanged();

    }

}
