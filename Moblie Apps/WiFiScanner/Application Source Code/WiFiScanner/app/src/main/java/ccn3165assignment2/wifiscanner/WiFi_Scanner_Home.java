package ccn3165assignment2.wifiscanner;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.view.View;

public class WiFi_Scanner_Home extends AppCompatActivity {

    private CardView start;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wifi__scanner_home);

        start = (CardView) findViewById(R.id.start);

        start.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(WiFi_Scanner_Home.this,WiFi_Scanner_Main.class);
                startActivity(intent);
            }
        });
    }
}
