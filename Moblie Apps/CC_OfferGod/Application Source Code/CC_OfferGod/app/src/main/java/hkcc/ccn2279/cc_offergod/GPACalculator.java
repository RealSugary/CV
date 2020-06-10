package hkcc.ccn2279.cc_offergod;

import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.CardView;
import android.view.View;

import android.widget.ImageView;
import android.widget.TextView;
import static java.lang.Float.parseFloat;
import android.content.Intent;

public class GPACalculator extends AppCompatActivity {

    private TextInputLayout textInputSem1;
    private TextInputLayout textInputSem2;
    private TextInputLayout textInputSem3;
    private TextInputLayout textInputSem4;
    private TextView tv_cgpa;
    private CardView btn_calculate;
    private CardView btn_clear;
    private ImageView btn_back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_gpacalculator);

        textInputSem1 = (TextInputLayout) findViewById(R.id.text_input_sem1);
        textInputSem2 = (TextInputLayout) findViewById(R.id.text_input_sem2);
        textInputSem3 = (TextInputLayout) findViewById(R.id.text_input_sem3);
        textInputSem4 = (TextInputLayout) findViewById(R.id.text_input_sem4);
        tv_cgpa = (TextView) findViewById(R.id.tv_cgpa);
        btn_calculate = (CardView) findViewById(R.id.btn_calculate);
        btn_clear = (CardView) findViewById(R.id.btn_clear);
        btn_back = (ImageView) findViewById(R.id.btn_back);

        // Calculate Button OnClickListener
        btn_calculate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                calculateCGPA();
            }
        });

        // Clear Button OnClickListener
        btn_clear.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                textInputSem1.getEditText().setText("");
                textInputSem2.getEditText().setText("");
                textInputSem3.getEditText().setText("");
                textInputSem4.getEditText().setText("");

            }
        });

        btn_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(GPACalculator.this,MainMenu.class);
                startActivity(intent);
            }
        });

    } // End onCreate

    private void calculateCGPA() {

        String cGPAresult;
        String str_sem1GPA = textInputSem1.getEditText().getText().toString().trim();
        String str_sem2GPA = textInputSem2.getEditText().getText().toString().trim();
        String str_sem3GPA = textInputSem3.getEditText().getText().toString().trim();
        String str_sem4GPA = textInputSem4.getEditText().getText().toString().trim();

        float cGPA = 0;


        // cGPA = parseFloat(str_sem1GPA);
        // cGPAresult = Float.toString(cGPA);
        // textInputSem2.setError(cGPAresult);

        try{
            if( str_sem1GPA.isEmpty() ) {                                      // Sem 1 GPA is empty
                textInputSem1.setError("Semaster 1 GPA cannot be empty");
            }else{                                                             // Have Sem 1 GPA, but don't have Sem 2 GPA
                if( str_sem2GPA.isEmpty() ) {
                    cGPA = parseFloat(str_sem1GPA);
                    cGPAresult = Float.toString(cGPA);
                    tv_cgpa.setText("Your CGPA is " + cGPAresult);
                }else{                                                        // Have Sem 1 and Sem 2 GPA, but don't have Sem 3 GPA
                    if ( str_sem3GPA.isEmpty() ) {
                        cGPA = ( parseFloat(str_sem1GPA) + parseFloat(str_sem2GPA) ) / 2;
                        cGPAresult = Float.toString(cGPA);
                        tv_cgpa.setText("Your CGPA is " + cGPAresult);
                    }else{                                                    // Have Sem 1, Sem 2 and Sem 3 GPA, but don't have Sem 4 GPA
                        if ( str_sem4GPA.isEmpty() ) {
                            cGPA = ( parseFloat(str_sem1GPA) + parseFloat(str_sem2GPA) + parseFloat(str_sem3GPA) ) / 3;
                            cGPAresult = Float.toString(cGPA);
                            tv_cgpa.setText("Your CGPA is " + cGPAresult);
                        }else{                                                // Have Sem 1, Sem 2, Sem 3 and Sem 4 GPA
                            cGPA = ( parseFloat(str_sem1GPA) + parseFloat(str_sem2GPA) + parseFloat(str_sem3GPA) + parseFloat(str_sem4GPA) ) / 4;
                            cGPAresult = Float.toString(cGPA);
                            tv_cgpa.setText("Your CGPA is " + cGPAresult);
                        }
                    }
                }
            }
        }catch( NumberFormatException e ) {
            textInputSem1.setError("Invalid Input, Please enter number");
            textInputSem2.setError("Invalid Input, Please enter number");
            textInputSem3.setError("Invalid Input, Please enter number");
            textInputSem4.setError("Invalid Input, Please enter number");
        }

    }

}
