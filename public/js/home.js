$(document).ready(function(){
    $.ajax({
        type: 'GET',
        url: '/getServicesPendingPermissions',
        contentType: "application/json; charset=utf-8",
        success: function (data) {
            var trHTML = '';
            
            $.each(data, function (i, permissions) {
                console.log(i, permissions);
                trHTML +='<tr><td><input type="checkbox" name="'+ permissions['id'] +'" value="true"> '+ permissions['name'] +'</td></tr>'
            });
            
            $('#permissionTable').append(trHTML);
        }
    });
});