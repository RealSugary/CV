package hkcc.ccn2279.cc_offergod;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

public class OfferSTATAdapter extends RecyclerView.Adapter<OfferSTATAdapter.ViewHolder> {

    private static final String TAG = "RecyclerViewAdapter";

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_year = new ArrayList<>();
    private ArrayList<String> str_avg = new ArrayList<>();
    private ArrayList<String> str_mini = new ArrayList<>();
    private ArrayList<String> str_max = new ArrayList<>();
    private Context mContext;

    public OfferSTATAdapter(ArrayList<String> str_uname, ArrayList<String> str_year, ArrayList<String> str_avg, ArrayList<String> str_mini, ArrayList<String> str_max, Context context) {
        this.str_uname = str_uname;
        this.str_year = str_year;
        this.str_avg = str_avg;
        this.str_mini = str_mini;
        this.str_max = str_max;
        this.mContext = context;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_stat, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG,"onBindViewHolder: called.");

        holder.tv_uname.setText(str_uname.get(position));
        holder.tv_year.setText(str_year.get(position));
        holder.tv_avg.setText(str_avg.get(position));
        holder.tv_mini.setText(str_mini.get(position));
        holder.tv_max.setText(str_max.get(position));

    }

    @Override
    public int getItemCount() {
        return str_uname.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView tv_uname;
        TextView tv_year;
        TextView tv_avg;
        TextView tv_mini;
        TextView tv_max;

        public ViewHolder(View itemView) {
            super(itemView);

            tv_uname = itemView.findViewById(R.id.tv_uname);
            tv_year = itemView.findViewById(R.id.tv_year);
            tv_avg = itemView.findViewById(R.id.tv_avg);
            tv_mini = itemView.findViewById(R.id.tv_mini);
            tv_max = itemView.findViewById(R.id.tv_max);
        }
    }
}
