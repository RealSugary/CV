package ccn3165assignment2.wifiscanner;


import android.Manifest;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

public class WiFi_Scanner_Main extends AppCompatActivity {

    private static final String TAG = "WiFi_Scanner_Main";

    // ArrayList
    private ArrayList<String> mSSID = new ArrayList<>();
    private ArrayList<String> mBSSID = new ArrayList<>();
    private ArrayList<String> mGPS = new ArrayList<>();
    // Database
    private DatabaseHelper mDatabaseHelper;
    // For WiFi
    private WifiManager mainWifi;
    private WifiReceiver receiverWifi;
    private List<ScanResult> wifiList;
    // For GPS Location
    private LocationManager locationManager;
    private LocationListener locationListener;
    private String gps_Location = "";
    // For List
    private RecyclerView listWifi;
    private RecyclerViewAdapter adapter;
    // For Refresh
    private Timer timer;
    private EditText scantime_edit;
    private int refreshtime = 0;
    private int refreshedtime = 0;
    private boolean refresh = false;
    // Simple Vars
    private TextView header, tvrefreshed;
    private FloatingActionButton savebtn, viewbtn, emailbtn, scanbtn;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wifi__scanner__main);

        scantime_edit = (EditText) findViewById(R.id.scantime_edit);
        header = (TextView) findViewById(R.id.header);
        tvrefreshed = (TextView) findViewById(R.id.tvrefreshed);
        savebtn = (FloatingActionButton) findViewById(R.id.savebtn);
        viewbtn = (FloatingActionButton) findViewById(R.id.viewbtn);
        emailbtn = (FloatingActionButton) findViewById(R.id.emailbtn);
        scanbtn = (FloatingActionButton) findViewById(R.id.scanbtn);

        mDatabaseHelper = new DatabaseHelper(this);

        timer = new Timer();

        mainWifi = (WifiManager) getSystemService(Context.WIFI_SERVICE);
        if (!mainWifi.isWifiEnabled()) {
            // If wifi disabled then enable it
            Toast.makeText(getApplicationContext(), "WiFi is Disabled.. Enabling Wi-Fi !!",
                    Toast.LENGTH_LONG).show();

            mainWifi.setWifiEnabled(true);
        }
        receiverWifi = new WifiReceiver();
        registerReceiver(receiverWifi, new IntentFilter(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION));

        initRecyclerView();
        gpsLocation();

        savebtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!mSSID.isEmpty()) {
                    addData();
                } else {
                    Toast.makeText(getApplicationContext(), "No WiFi in the List.", Toast.LENGTH_SHORT).show();
                }
            }
        });

        viewbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(WiFi_Scanner_Main.this, WiFi_Scanner_SaveList.class);
                startActivity(intent);
            }
        });

        emailbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(WiFi_Scanner_Main.this, WiFi_Scanner_Email.class);
                intent.putExtra("SSID", mSSID);
                intent.putExtra("BSSID", mBSSID);
                intent.putExtra("GPS Location", mGPS);
                startActivity(intent);

            }
        });

        scanbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListener);
                mainWifi.startScan();
                Snackbar.make(v, "Scanning WiFi...", Snackbar.LENGTH_SHORT).show();
                mSSID.clear();
                mBSSID.clear();
                mGPS.clear();
                validateInterval();
                if (refresh)
                    Snackbar.make(v, "Refresh every " + refreshtime / 1000 + " seconds...", Snackbar.LENGTH_SHORT).show();
                refreshScan();
            }
        });

    }

    @Override
    protected void onPause() {
        unregisterReceiver(receiverWifi);
        super.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
        registerReceiver(receiverWifi, new IntentFilter(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION));
        listWifi.setAdapter(adapter);
    }

    // Scan
    public void initRecyclerView() {
        listWifi = findViewById(R.id.listWifi);
        adapter = new RecyclerViewAdapter(mSSID, mBSSID, mGPS, this);
        listWifi.setAdapter(adapter);
        listWifi.setLayoutManager(new LinearLayoutManager(this));
    }

    public void gpsLocation() {

        locationManager = (LocationManager) getSystemService(LOCATION_SERVICE);

        locationListener = new LocationListener() {
            @Override
            public void onLocationChanged(Location location) {
                gps_Location = location.getLatitude() + ", " + location.getLongitude();
            }

            @Override
            public void onStatusChanged(String provider, int status, Bundle extras) {

            }

            @Override
            public void onProviderEnabled(String provider) {

            }

            @Override
            public void onProviderDisabled(String provider) {
                Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                startActivity(intent);
            }
        };
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                requestPermissions(new String[] {
                        Manifest.permission.ACCESS_FINE_LOCATION,Manifest.permission.ACCESS_COARSE_LOCATION,
                        Manifest.permission.INTERNET
                }, 10);
                return;
            }
        }else {
            configureButton();
        }

    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode) {
            case 10:
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED)
                    configureButton();
                return;
        }

    }

    private void configureButton() {

        scanbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListener);
                mainWifi.startScan();
                Snackbar.make(v, "Scanning WiFi...", Snackbar.LENGTH_SHORT).show();
                mSSID.clear();
                mBSSID.clear();
                mGPS.clear();
                validateInterval();
                if (refresh)
                    Snackbar.make(v, "Refresh every " + refreshtime / 1000 + " seconds...", Snackbar.LENGTH_SHORT).show();
                refreshScan();
            }
        });

    }

    public void validateInterval() {
        String srefreshtime = "" + scantime_edit.getText().toString();

        if( srefreshtime == "") {
            refreshtime = 0;
        }else {
            refresh = true;
            refreshtime = Integer.parseInt(srefreshtime) * 1000;
        }
    }

    public void refreshScan() {
        if(refresh) {
            timer.schedule(new TimerTask() {
                @Override
                public void run() {
                    refreshedtime++;
                    mainWifi.startScan();
                    mSSID.clear();
                    mBSSID.clear();
                    mGPS.clear();
                    validateInterval();
                    refreshScan();
                }
            },refreshtime);
        }
    }

    // Database
    public void addData() {
        for(int i = 0; i < mSSID.size(); i++) {
           boolean insertData =  mDatabaseHelper.addRow(mSSID.get(i).toString(), mBSSID.get(i).toString(), mGPS.get(i).toString() );

            if(insertData) {
                Toast.makeText(this,"SSID Successfully Inserted!", Toast.LENGTH_SHORT).show();
            }else {
                Toast.makeText(this,"Error: Failed To Add a Row.", Toast.LENGTH_SHORT).show();
            }
        }
    }


    // WiFi Receiver class
    class WifiReceiver extends BroadcastReceiver {
        @Override
        public void onReceive(Context context, Intent intent) {
            wifiList = mainWifi.getScanResults();

            for(int i = 0; i < wifiList.size(); i++) {
                mSSID.add(wifiList.get(i).SSID.toString());
                mBSSID.add(wifiList.get(i).BSSID.toString());
                mGPS.add(gps_Location);
            }
            header.setText(" Number Of Wifi connections : "+ wifiList.size());
            tvrefreshed.setText("Refresh Times: " + refreshedtime);

            adapter.notifyDataSetChanged();
        }
    }


} // End WiFi_Scanner_Main
