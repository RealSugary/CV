package ccn3165assignment2.wifiscanner;

import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;

import java.util.ArrayList;

public class WiFi_Scanner_SaveList extends AppCompatActivity {

    private static final String TAG = "ListDataActivity";

    private ArrayList<String> listSSID = new ArrayList<>();
    private ArrayList<String> listBSSID = new ArrayList<>();
    private ArrayList<String> listGPS = new ArrayList<>();

    private DatabaseHelper mDatabaseHelper;

    private RecyclerView mSaveList;
    private RecyclerViewAdapter adapter;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wifi_scanner_savelist);

        mDatabaseHelper = new DatabaseHelper(this);

       initSaveList();
    }

    private void initSaveList() {
        Log.d(TAG, "initSaveList: Displaying data in the SaveList.");

        mSaveList = (RecyclerView) findViewById(R.id.listSave);
        adapter = new RecyclerViewAdapter(listSSID, listBSSID, listGPS, this);
        mSaveList.setAdapter(adapter);
        mSaveList.setLayoutManager(new LinearLayoutManager(this));

        mDatabaseHelper.getReadableDatabase();
        Cursor data = mDatabaseHelper.getdata();

        if(data != null) {
            if(data.moveToFirst()) {
                do {
                    listSSID.add(data.getString(0)); // SSID
                    listBSSID.add(data.getString(1)); // BSSUD
                    listGPS.add(data.getString(2)); // GPS Location
                } while(data.moveToNext());
            }
        }

        adapter.notifyDataSetChanged();

    }

}
