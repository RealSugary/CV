$(document).ready(function(){

   $(".btn-plus").click(function(){
      $(this).parent().children(".gift-qty-label").html(parseInt($(this).parent().children(".gift-qty-label").html()) + 1);
      $(this).parent().children(".gift-qty").val(parseInt($(this).parent().children(".gift-qty").val()) + 1);
   });

   $(".btn-minus").click(function(){
      $(this).parent().children(".gift-qty-label").html(parseInt($(this).parent().children(".gift-qty-label").html()) - 1);
      $(this).parent().children(".gift-qty").val(parseInt($(this).parent().children(".gift-qty").val()) - 1);
   });

});

var checklist = document.getElementById("checklist");
var modal = document.getElementById("buygiftModal");

function checkout() {
   var i;
   var x;
   var row;
   var j=1;
   var sum=0;
   var gift_id;
   var gift_qty;

   var table = document.getElementById("checklist");

   for( i = 1;i < 10;i++ ) {
     x = document.getElementById("id_"+i).value;

      if(x!=0) {
         row = table.insertRow(j);

         cell1 = row.insertCell(0);
         cell2 = row.insertCell(1);
         cell3 = row.insertCell(2);

         cell1.innerHTML = document.getElementById("gift_"+i).innerHTML;
         cell2.innerHTML = document.getElementById("id_"+i).value;
         cell3.innerHTML = cell2.innerHTML * document.getElementById("gift_"+i+"_price").innerHTML;
         sum += parseInt(cell3.innerHTML);
         j++;

         gift_id = document.createElement("INPUT");
         gift_id.name="gift_id[]";
         gift_id.value = i;
         gift_id.type = 'hidden';

         gift_qty = document.createElement("INPUT");
         gift_qty.name="gift_qty[]";
         gift_qty.value = cell2.innerHTML;
         gift_qty.type = 'hidden';

         document.getElementById("cart").appendChild(gift_id);
         document.getElementById("cart").appendChild(gift_qty);

      }

   }
   gift_amount = document.createElement("INPUT");
   gift_amount.name="gift_amount";
   gift_amount.value = sum;
   gift_amount.type = 'hidden';
   document.getElementById("cart").appendChild(gift_amount);

   if (checklist.rows.length == 1) {
      document.getElementById("btn_pay").style.display = "none";
   } else {
      document.getElementById("btn_pay").style.display = "block";
   }
   document.getElementById("total").innerHTML = " " + sum;

}

function delrow() {
      var i;
      for (i=1;i<=checklist.rows.length;i++){
         checklist.deleteRow(i);
      }
}

window.onclick = function(event) {
   if (event.target == modal) {
      var i;
      for (i=1;i<=checklist.rows.length;i++){
      checklist.deleteRow(i);
      }
   }
}
