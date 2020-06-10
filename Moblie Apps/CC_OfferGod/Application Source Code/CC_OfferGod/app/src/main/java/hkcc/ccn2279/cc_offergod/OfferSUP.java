package hkcc.ccn2279.cc_offergod;

import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;
import java.util.ArrayList;

public class OfferSUP extends AppCompatActivity implements AdapterView.OnItemSelectedListener {

    private ImageView btn_back;
    private Spinner spinner_sup;

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_asssoprogram = new ArrayList<>();
    private ArrayList<String> str_mini = new ArrayList<>();

    private RecyclerView recyclerView_sup;
    private OfferSUPAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offersup);

        btn_back = (ImageView) findViewById(R.id.btn_back);
        spinner_sup = (Spinner) findViewById(R.id.spinner_sup);

        populateSpinner();

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OfferSUP.this,OfferRecord.class);
                startActivity(intent);
            }
        });

    }

    private void populateSpinner() {

        ArrayList<String> spinnerdata = new ArrayList<>();

        spinnerdata.add(0, "");
        DatabaseAccess databaseAccess = DatabaseAccess.getInstance(getApplicationContext()) ;
        databaseAccess.open();

        Cursor data = databaseAccess.getUniversityProgram();

        if ( data != null ) {
            if ( data.moveToFirst() ) {
                do{
                    spinnerdata.add( data.getString(0) );
                }while ( data.moveToNext() );
            }
        }else {
            databaseAccess.close();
        }

        String[] allSpinner = new String[spinnerdata.size()];
        allSpinner = spinnerdata.toArray(allSpinner);

        ArrayAdapter<String> spinnerAdapter = new ArrayAdapter<String>(OfferSUP.this,android.R.layout.simple_spinner_item, allSpinner);
        spinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_item);
        spinner_sup.setAdapter(spinnerAdapter);
        spinner_sup.setSelection(0);
        spinner_sup.setOnItemSelectedListener(this);

    }

    @Override
    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
        String selected_uprogram = adapterView.getItemAtPosition(i).toString().trim();
        Toast.makeText(adapterView.getContext(), selected_uprogram, Toast.LENGTH_SHORT).show();
        displayData(selected_uprogram);
    }

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }

    public void displayData(String uprogram){

        recyclerView_sup = (RecyclerView) findViewById(R.id.recyclerView_sup);
        adapter = new OfferSUPAdapter(str_uname, str_asssoprogram, str_mini, this);
        recyclerView_sup.setAdapter(adapter);
        recyclerView_sup.setLayoutManager( new LinearLayoutManager(this) );

        adapter.clearView();

        DatabaseAccess databaseAccess = DatabaseAccess.getInstance(getApplicationContext()) ;
        databaseAccess.open();

        Cursor data = databaseAccess.getsup(uprogram);

        if ( data != null ) {
            if ( data.moveToFirst() ) {
                do{
                    str_uname.add( data.getString(0) );
                    str_asssoprogram.add( data.getString(1) );
                    str_mini.add( data.getString(2) );
                }while ( data.moveToNext() );
            }
        }else {
            databaseAccess.close();
        }

        adapter.notifyDataSetChanged();

    }

}
