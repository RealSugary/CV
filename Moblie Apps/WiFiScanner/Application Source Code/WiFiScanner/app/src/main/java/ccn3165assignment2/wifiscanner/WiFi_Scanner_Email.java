package ccn3165assignment2.wifiscanner;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import java.util.ArrayList;

public class WiFi_Scanner_Email extends AppCompatActivity {

    // ArrayList
    private ArrayList<String> mSSID = new ArrayList<>();
    private ArrayList<String> mBSSID = new ArrayList<>();
    private ArrayList<String> mGPS = new ArrayList<>();

    private EditText recipient_edit, subject_edit, message_edit;
    private Button btnSend;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wifi_scanner_email);

        Intent intent = getIntent();
        mSSID = intent.getStringArrayListExtra("SSID");
        mBSSID = intent.getStringArrayListExtra("BSSID");
        mGPS = intent.getStringArrayListExtra("Location");

        recipient_edit = (EditText) findViewById(R.id.recipient_edit);
        subject_edit = (EditText) findViewById(R.id.subject_edit);
        message_edit = (EditText) findViewById(R.id.message_edit);

        btnSend = (Button) findViewById(R.id.btnSend);

        btnSend.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendMail();
            }
        });

    }

    public void sendMail() {
        String recipientList = recipient_edit.getText().toString();
        String[] recipients = recipientList.split(",");

        String subject = subject_edit.getText().toString();
        String message = message_edit.getText().toString();

        Intent intent = new Intent(Intent.ACTION_SEND);
        intent.putExtra(Intent.EXTRA_EMAIL, recipients);
        intent.putExtra(Intent.EXTRA_SUBJECT, subject);
        intent.putExtra(Intent.EXTRA_TEXT, message);

        String databaseUri = "file:///data/data/ccn3165assignment2.wifiscanner/databases/wifi_table";
        intent.putExtra(Intent.EXTRA_STREAM,databaseUri);
        intent.setType("message/rfc822");
        startActivity(Intent.createChooser(intent, "Choose an Email Client"));
    }

}
