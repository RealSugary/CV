package ccn3165assignment2.wifiscanner;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

public class RecyclerViewAdapter extends RecyclerView.Adapter<RecyclerViewAdapter.ViewHolder> {

    private static final String TAG = "RecyclerViewAdapter";

    private ArrayList<String> mSSID = new ArrayList<>();
    private ArrayList<String> mBSSID = new ArrayList<>();
    private ArrayList<String> mGPS = new ArrayList<>();
    private Context mContext;

    public RecyclerViewAdapter(ArrayList<String> mSSID, ArrayList<String> mBSSID, ArrayList<String> mGPS, Context context) {
        this.mSSID = mSSID;
        this.mBSSID = mBSSID;
        this.mGPS = mGPS;
        this.mContext = context;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_wifi, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG,"onBindViewHolder: called.");

        holder.tvSSID.setText(mSSID.get(position));
        holder.tvBSSID.setText(mBSSID.get(position));
        holder.tvLocation.setText(mGPS.get(position));
    }

    @Override
    public int getItemCount() {
        return mSSID.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView tvSSID;
        TextView tvBSSID;
        TextView tvLocation;

        public ViewHolder(View itemView) {
            super(itemView);
            tvSSID = itemView.findViewById(R.id.tvSSID);
            tvBSSID = itemView.findViewById(R.id.tvBSSID);
            tvLocation = itemView.findViewById(R.id.tvLocation);

        }
    }
}
