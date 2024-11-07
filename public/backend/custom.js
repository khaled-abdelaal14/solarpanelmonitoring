$(document).ready(function(){
    //checkadminpasswordcorrect
    $("#currentpassword").keyup(function(){
        var currentpassword=$("#currentpassword").val();
        // alert(currentpassword);s
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'/admin/check-admin-password',
            data:{currentpassword:currentpassword},
            success:function(resp){
                // alert(resp);
                if(resp=="false"){

                    $("#check_password").html("<font color='red'> Current password is Incorrect </font>");
                }else if(resp=="true"){
                    $("#check_password").html("<font color='green'> Current password is Correct </font>");

                }

            },error:function(){
                alert('Error');
                
            }
        });
    })
    $("#currentpasswordstudent").keyup(function(){
        var currentpassword=$("#currentpasswordstudent").val();
        // alert(currentpassword);s
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'/student/check-admin-password',
            data:{currentpassword:currentpassword},
            success:function(resp){
                // alert(resp);
                if(resp=="false"){

                    $("#check_password").html("<font color='red'> Current password is Incorrect </font>");
                }else if(resp=="true"){
                    $("#check_password").html("<font color='green'> Current password is Correct </font>");

                }

            },error:function(){
                alert('Error');
                
            }
        });
    })

    $(document).on("click",".confirmdelete",function(){
        var record=$(this).attr("record");
        var recordid=$(this).attr("recordid");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
              window.location.href="/admin/delete-"+record+"/"+recordid;
            }
          })
    })

  

    
  
})