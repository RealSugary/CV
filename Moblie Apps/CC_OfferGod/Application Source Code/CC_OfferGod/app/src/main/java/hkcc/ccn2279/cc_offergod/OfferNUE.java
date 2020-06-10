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

public class OfferNUE extends AppCompatActivity {

    private ImageView btn_back;

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_nue = new ArrayList<>();

    private RecyclerView recyclerView_nue;
    private OfferNUEAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offernue);

        btn_back = (ImageView) findViewById(R.id.btn_back);

        displayData();

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferNUE.this,OfferRecord.class);
                startActivity(intent);
            }
        });

    }

    private void displayData() {

        recyclerView_nue = (RecyclerView) findViewById(R.id.recyclerView_nue);
        adapter = new OfferNUEAdapter(str_uname, str_nue, this);
        recyclerView_nue.setAdapter(adapter);
        recyclerView_nue.setLayoutManager( new LinearLayoutManager(this) );

        DatabaseAccess databaseAccess = DatabaseAccess.getInstance(getApplicationContext()) ;
        databaseAccess.open();

        Cursor data = databaseAccess.getnue();

        if ( data != null ) {
            if ( data.moveToFirst() ) {
                do{
                    str_uname.add( data.getString(0) );
                    str_nue.add( data.getString(1) );
                }while ( data.moveToNext() );
            }
        }else {
            databaseAccess.close();
        }

        adapter.notifyDataSetChanged();

    }

}
