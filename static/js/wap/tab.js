// tasktab
function setTab(name,cursel,n,css){
 for(i=1;i<=n;i++){
  var menu=document.getElementById(name+i);
  var con=document.getElementById("con_"+name+"_"+i);
  menu.className=i==cursel?css:"";
  con.style.display=i==cursel?"block":"none";
 }
}

   