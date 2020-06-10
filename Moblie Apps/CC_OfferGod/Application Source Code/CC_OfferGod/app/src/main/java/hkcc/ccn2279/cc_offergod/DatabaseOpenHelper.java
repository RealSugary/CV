package hkcc.ccn2279.cc_offergod;
import android.content.Context;

import com.readystatesoftware.sqliteasset.SQLiteAssetHelper;

public class DatabaseOpenHelper extends SQLiteAssetHelper {
    private static final String DATABASE_NAME="laedbms.sqlite";
    private static final int DATABASE_VERSION=1;

    // Constructor
    public  DatabaseOpenHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

}
