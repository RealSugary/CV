package hkcc.ccn2279.cc_offergod;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import java.util.ArrayList;

public class OfferSUPAdapter extends RecyclerView.Adapter<OfferSUPAdapter.ViewHolder> {

    private static final String TAG = "RecyclerViewAdapter";

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_assoprogram = new ArrayList<>();
    private ArrayList<String> str_mini = new ArrayList<>();
    private Context mContext;

    public OfferSUPAdapter(ArrayList<String> str_uname, ArrayList<String> str_assoprogram, ArrayList<String> str_mini, Context context) {
        this.str_uname = str_uname;
        this.str_assoprogram = str_assoprogram;
        this.str_mini = str_mini;
        this.mContext = context;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_sup, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG,"onBindViewHolder: called.");

        holder.tv_uname.setText(str_uname.get(position));
        holder.tv_assoprogram.setText(str_assoprogram.get(position));
        holder.tv_mini.setText(str_mini.get(position));

    }

    @Override
    public int getItemCount() {
        return str_uname.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView tv_uname;
        TextView tv_assoprogram;
        TextView tv_mini;

        public ViewHolder(View itemView) {
            super(itemView);

            tv_uname = itemView.findViewById(R.id.tv_uname);
            tv_assoprogram = itemView.findViewById(R.id.tv_assoprogram);
            tv_mini = itemView.findViewById(R.id.tv_mini);
        }
    }

    public void clearView() {
        str_uname.clear();
        str_assoprogram.clear();
        str_mini.clear();
        notifyDataSetChanged();
    }

}
