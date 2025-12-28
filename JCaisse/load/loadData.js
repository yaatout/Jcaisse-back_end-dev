    $(document).ready(function() {  

        localStorage.clear();
        
        $.ajax({
            url:"load/loadData.php",
            method:"POST",
            data:{ 
                operation:'1',
            },
            success: function (data) {
                var details = JSON.parse(data);
                var taille = details.length;
                for(var i = 0; i<taille; i++){
                    table="Designation_"+i;
                    localStorage.setItem(table, JSON.stringify(details[i]));
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });

        $.ajax({
            url:"load/loadData.php",
            method:"POST",
            data:{
                operation:'2',
             },
            success: function (data) {
                var tab = data.split("<>");
                localStorage.setItem("Client", tab[0]);
                localStorage.setItem("Compte", tab[1]);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });

    });


