package ccn3165assignment2.wifiscanner;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

public class DatabaseHelper extends SQLiteOpenHelper {

    private static final String TAG = "DatabaseHelper";

    private static final String TABLE_NAME = "wifi_table";
    private static final String COL1 = "ID";
    private static final String COL2 = "SSID";
    private static final String COL3 = "BSSID";
    private static final String COL4 = "GPS_Location";

    public DatabaseHelper(Context context) {
        super(context, TABLE_NAME, null,1);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String createTable = "CREATE TABLE " + TABLE_NAME + " (" + COL1 + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                COL2 + " TEXT," + COL3 + " TEXT," + COL4 + " TEXT)";
        db.execSQL(createTable);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        onCreate(db);
    }

    public  boolean addRow( String listSSID, String listBSSID, String listGPS) {
        SQLiteDatabase db = this.getWritableDatabase();
        ContentValues contentRow = new ContentValues();

        contentRow.put(COL2, listSSID);
        contentRow.put(COL3, listBSSID);
        contentRow.put(COL4, listGPS);

        Log.d(TAG,"addSSID: Adding " + listSSID + ", " + listBSSID + ", " + listGPS + " to " + TABLE_NAME + " in a row.");

        long result = db.insert(TABLE_NAME, null, contentRow);

        // if data as inserted incorrectly it will return -1.
        if(result == -1) {
            return false;
        }else {
            return true;
        }
    }

    public Cursor getdata() {
        SQLiteDatabase db = this.getWritableDatabase();

        String query = "SELECT " + COL2 + ", " + COL3 + ", " + COL4 + " FROM " + TABLE_NAME;
        Cursor data = db.rawQuery(query, null);

        return data;
    }

}
