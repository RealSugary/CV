package hkcc.ccn2279.cc_offergod;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DatabaseAccess {
    private SQLiteOpenHelper openHelper;
    private SQLiteDatabase db;
    private static DatabaseAccess instance;
    Cursor c = null;

    // Private constructor so that object creation from outside the class is avoided

    private DatabaseAccess(Context context) {
        this.openHelper = new DatabaseOpenHelper(context);
    }

    // To return the single instance of database

    public static DatabaseAccess getInstance(Context context) {
        if(instance == null) {
            instance = new DatabaseAccess(context);
        }
        return instance;
    }

    // To open the database

    public void open() {
        this.db = openHelper.getWritableDatabase();
    }

    // To close the database

    public void close() {
        if(db != null) {
            this.db.close();
        }
    }

    // Validate User

    public String findUser(String user) {

        c = db.rawQuery(" Select Password From User_Info Where UserName = '"+ user +"' ", new String[] {});

        StringBuffer buffer = new StringBuffer();

        while( c.moveToNext() ) {
            String result = c.getString(0);
            buffer.append("" + result);
        }
        return buffer.toString();
    }


    // Search through Asso Programme

    public Cursor getAssoProgram() {

        c = db.rawQuery(
                "SELECT Program_Name\n" +
                        "FROM Asso_Programme\n" +
                        "ORDER BY Program_Name", null);

        return c;
    }

    public Cursor getsap(String asso) {

        c = db.rawQuery(
                "SELECT i.UniversityID, u.UProgramName, Round(Min(((s1gpa+s2gpa+s3gpa+s4gpa)/4)),2)\n" +
                        "FROM Student_Info AS s, Offer_Record AS o,  U_Programme AS u,  Asso_Programme AS a, University AS i\n" +
                        "WHERE s.Program_Code=a.Program_Code \n" +
                        "AND s.StudentID=o.StudentID \n" +
                        "AND o.UProgram_Code=u.UProgram_Code\n" +
                        "AND u.UniversityID=i.UniversityID\n" +
                        "AND Program_Name = '"+ asso +"' \n" +
                        "GROUP BY a.Program_Code, u.UProgram_Code, u.UProgramName\n" +
                        "ORDER BY a.Program_Code, u.UProgram_Code;", null);

        return c;
    }

    // Search through University Programme

    public Cursor getUniversityProgram() {

        c = db.rawQuery(
                "SELECT UProgramName\n" +
                        "FROM U_Programme\n" +
                        "GROUP BY UProgramName\n" +
                        "ORDER BY UProgramName;", null);

        return c;
    }

    public Cursor getsup(String uprogram) {

        c = db.rawQuery(
                "SELECT u.UniversityID, a.Program_Name, Round(Min(((s1gpa+s2gpa+s3gpa+s4gpa)/4)),2)\n" +
                        "FROM Asso_Programme AS a, U_Programme AS u, Student_Info AS s, Offer_Record AS o\n" +
                        "WHERE s.StudentID=o.StudentID\n" +
                        "AND s.Program_Code=a.Program_Code\n" +
                        "AND o.UProgram_Code=u.UProgram_Code\n" +
                        "AND u.UProgramName = '"+ uprogram +"' \n" +
                        "GROUP BY o.StudentID,o.UProgram_Code\n" +
                        "ORDER BY 3;", null);

        return c;
    }

    // Number of university entry
    public Cursor getnue() {

        c = db.rawQuery(
                "SELECT e.UniversityID, Count(*)\n" +
                        "FROM Offer_Record AS b, U_Programme AS d, University AS e \n" +
                        "WHERE b.UProgram_Code=d.UProgram_Code AND d.UniversityID=e.UniversityID \n" +
                        "GROUP BY e.UniversityID\n" +
                        "ORDER BY Count(*)", null);

        return c;
    }

    // Statistic Summary
    public Cursor getstat() {

        c = db.rawQuery(
                "SELECT n.UniversityID, s.Year_Graduated, Round(Avg((s.s1gpa+s.s2gpa+s.s3gpa+s.s4gpa)/4),2), Round(Min((s.s1gpa+s.s2gpa+s.s3gpa+s.s4gpa)/4),2), Round(Max((s.s1gpa+s.s2gpa+s.s3gpa+s.s4gpa)/4),2) \n" +
                        "FROM Offer_Record AS o, U_Programme AS u, student_Info AS s, University AS n\n" +
                        "WHERE s.StudentID=o.StudentID\n" +
                        "AND o.UProgram_Code=u.UProgram_Code\n" +
                        "AND u.UniversityID=n.UniversityID\n" +
                        "GROUP BY n.University_Desc, s.Year_Graduated\n" +
                        "ORDER BY 1, 2;", null);

        return c;
    }


    // Query and return the result from database (Example)

    public String getResult() {

        c = db.rawQuery("Select * From Offer_Record", new String[] {});

        StringBuffer buffer = new StringBuffer();

        while( c.moveToNext() ) {
            String result = c.getString(0);
            buffer.append("" + result);
        }
        return buffer.toString();
    }

}