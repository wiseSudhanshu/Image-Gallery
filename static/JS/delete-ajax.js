const deleteData = (id) => {
    $(document).ready(function() {
        $.ajax({
            // Action
            url: 'postAction.php',
            type: 'POST',
            data: {
                id: id,
                action: 'delete'
            },
            success: function(response) {
                if(response) {
                    alert("Data Deleted Permanently");
                    document.getElementById(id).style.display = "none";
                }
                else {
                    alert("Data could not be deleted!");
                }
            }
        })
    })
}