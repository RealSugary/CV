package hkcc.ccn2279.cc_offergod;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

public class OfferNUEAdapter extends RecyclerView.Adapter<OfferNUEAdapter.ViewHolder> {

    private static final String TAG = "RecyclerViewAdapter";

    private ArrayList<String> str_uname = new ArrayList<>();
    private ArrayList<String> str_nue = new ArrayList<>();
    private Context mContext;

    public OfferNUEAdapter(ArrayList<String> str_uname, ArrayList<String> str_nue, Context context) {
        this.str_uname = str_uname;
        this.str_nue = str_nue;
        this.mContext = context;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_nue, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG,"onBindViewHolder: called.");

        holder.tv_uname.setText(str_uname.get(position));
        holder.tv_nue.setText(str_nue.get(position));

    }

    @Override
    public int getItemCount() {
        return str_uname.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView tv_uname;
        TextView tv_nue;

        public ViewHolder(View itemView) {
            super(itemView);

            tv_uname = itemView.findViewById(R.id.tv_uname);
            tv_nue = itemView.findViewById(R.id.tv_nue);
        }
    }
}
