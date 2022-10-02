
// $(document).ready(function () {
//   $('select').on('change', function () {
//     alert(this.status);
//   });
// });

function my_status_change(element)
{
  var id= $(element).children('option:selected').data('id');
  let status = $(element).children('option:selected').data('status');
  // alert(status);
  if(status === "approved"){
    swal({
      title: "Are you sure?",
      text: "You want to approve this post.",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url:'admin-ajax.php',
          type:'POST',
          data:{
            id:id,
            status:status,
            action:'update_status',
          },
          dataType:"JSON",
          success:function(resp){
            if(resp.code === 200){
              swal("Good job!", resp.message, "success");
              setTimeout(function(){
                location.reload();
               }, 1000);
            }
          }
        });
      }
    });
  }
  if(status === "rejected"){
    swal({
      title: "Are you sure?",
      text: "You want to reject this post.",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url:'admin-ajax.php',
          type:'POST',
          data:{
            id:id,
            status:status,
            action:'update_status',
          },
          dataType:"JSON",
          success:function(resp){
            if(resp.code === 200){
              swal("Good job!", resp.message, "success");
              setTimeout(function(){
                location.reload();
               }, 1000);
            }
          }
        });
      }
    });
  }
}